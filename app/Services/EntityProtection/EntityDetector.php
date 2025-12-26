<?php

namespace App\Services\EntityProtection;

/**
 * EntityDetector
 * 
 * Detects protected entities in text using Regex + Heuristic NLP
 * These entities should NEVER be translated in certified documents
 */
class EntityDetector
{
    /**
     * Detect all protected entities in the given text
     * 
     * @param string $text Source text to analyze
     * @param array $options Optional parameters (source_language, target_language, etc.)
     * @return array List of detected entities with type and value
     */
    public function detect(string $text, array $options = []): array
    {
        $entities = [];

        // ========================================
        // 1) REGEX PATTERNS (High Confidence)
        // ========================================
        $patterns = [
            // Certificate IDs
            'CERT_ID' => '/\bCT-\d{4}-\d{2}-\d{8}\b/u',
            
            // Financial Identifiers
            'IBAN' => '/\b[A-Z]{2}\d{2}[ ]?[A-Z0-9]{4}[ ]?[A-Z0-9]{4}[ ]?[A-Z0-9]{4}[ ]?[A-Z0-9]{4}[ ]?[A-Z0-9]{0,2}\b/u',
            'BIC' => '/\b[A-Z]{4}[A-Z]{2}[A-Z0-9]{2}([A-Z0-9]{3})?\b/u',
            'MONEY_EU' => '/€\s?\d{1,3}(\.\d{3})*(,\d{2})?/u',
            
            // Contact Information
            'EMAIL' => '/\b[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}\b/iu',
            'URL' => '/\bhttps?:\/\/[^\s]+/iu',
            
            // Dutch Identifiers
            'KVK' => '/\bKvK(?:-nummer)?[:\s]*\d{8}\b/iu',
            'VAT_NL' => '/\bNL\d{9}B\d{2}\b/iu',
            'BSN' => '/\bBSN[:\s]*\d{8,9}\b/iu',
            'AGB' => '/\bAGB(?:-code)?[:\s]*\d{6,12}\b/iu',
            'POSTCODE_NL' => '/\b\d{4}\s?[A-Z]{2}\b/u',
            
            // Document References
            'INVOICE_NO' => '/\b(Factuurnummer|Invoice\s+No\.?|Rechnungsnummer)[:\s]*[A-Z0-9\*\/\-]{6,}\b/iu',
            'PAYMENT_REF' => '/\b(Betalingskenmerk|Betalingsreferentie|Payment\s+Reference)[:\s]*[\d\s]{8,}\b/iu',
            'CASE_NO' => '/\b(Zaaknummer|Case\s+No\.?|Aktenzeichen)[:\s]*[A-Z0-9\-\/]{6,}\b/iu',
            
            // Dates (various formats)
            'DATE_NL' => '/\b\d{2}-\d{2}-\d{4}\b/u',
            'DATE_ISO' => '/\b\d{4}-\d{2}-\d{2}\b/u',
            
            // ID Numbers
            'ID_NUMBER' => '/\b(ID|Passport|Paspoort)\s*(?:No\.?|nummer)?[:\s]*[A-Z0-9]{6,12}\b/iu',
        ];

        foreach ($patterns as $type => $regex) {
            if (preg_match_all($regex, $text, $matches)) {
                foreach ($matches[0] as $value) {
                    $entities[] = [
                        'type' => $type,
                        'value' => $value,
                        'confidence' => 'high'
                    ];
                }
            }
        }

        // ========================================
        // 2) HEURISTIC NLP (Medium Confidence)
        // ========================================
        
        // Company names with legal suffixes
        $companySuffixes = '(B\.V\.|N\.V\.|GmbH|UG|AG|Ltd\.?|LLC|Inc\.?|S\.A\.|S\.r\.l\.|SARL|BVBA|VZW|Stichting|Vereniging|Foundation|Ziekenhuis|Hospital|Clinic|Kliniek|Universiteit|University)';
        if (preg_match_all('/\b([A-Z][\p{L}\p{M}\.\-&]+(?:\s+[A-Z][\p{L}\p{M}\.\-&]+){0,6})\s+' . $companySuffixes . '\b/u', $text, $matches)) {
            foreach ($matches[0] as $value) {
                $entities[] = [
                    'type' => 'ORG',
                    'value' => $value,
                    'confidence' => 'high'
                ];
            }
        }

        // Capitalized sequences (likely proper nouns)
        // Pattern: 2-5 consecutive capitalized words
        if (preg_match_all('/\b([A-Z][\p{L}\p{M}]+(?:\s+[A-Z][\p{L}\p{M}]+){1,4})\b/u', $text, $matches)) {
            foreach ($matches[1] as $value) {
                // Skip short sequences and common words
                if (mb_strlen($value) < 6) continue;
                
                // Skip if it's likely a sentence start
                if ($this->isLikelySentenceStart($value, $text)) continue;
                
                $entities[] = [
                    'type' => 'PROPER_NOUN',
                    'value' => $value,
                    'confidence' => 'medium'
                ];
            }
        }

        // ========================================
        // 3) POST-PROCESSING
        // ========================================
        
        // Remove duplicates and prefer longest matches
        $entities = $this->uniquePreferLongest($entities);
        
        // Filter by confidence if needed
        if (isset($options['min_confidence'])) {
            $entities = $this->filterByConfidence($entities, $options['min_confidence']);
        }

        return $entities;
    }

    /**
     * Check if a capitalized sequence is likely a sentence start
     */
    private function isLikelySentenceStart(string $value, string $text): bool
    {
        // Check if preceded by sentence-ending punctuation
        $pos = mb_strpos($text, $value);
        if ($pos === false || $pos < 2) return false;
        
        $before = mb_substr($text, $pos - 2, 2);
        return preg_match('/[.!?]\s$/', $before) === 1;
    }

    /**
     * Remove duplicates and prefer longest matches
     */
    private function uniquePreferLongest(array $entities): array
    {
        // Remove exact duplicates
        $seen = [];
        $out = [];
        
        foreach ($entities as $entity) {
            $key = $entity['type'] . '|' . $entity['value'];
            if (isset($seen[$key])) continue;
            
            $seen[$key] = true;
            $out[] = $entity;
        }

        // Sort by length (descending) to prevent partial replacement collisions
        usort($out, function($a, $b) {
            return mb_strlen($b['value']) <=> mb_strlen($a['value']);
        });

        return $out;
    }

    /**
     * Filter entities by confidence level
     */
    private function filterByConfidence(array $entities, string $minConfidence): array
    {
        $levels = ['low' => 1, 'medium' => 2, 'high' => 3];
        $minLevel = $levels[$minConfidence] ?? 1;
        
        return array_filter($entities, function($entity) use ($levels, $minLevel) {
            $entityLevel = $levels[$entity['confidence'] ?? 'low'] ?? 1;
            return $entityLevel >= $minLevel;
        });
    }
}
