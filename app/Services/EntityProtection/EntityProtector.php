<?php

namespace App\Services\EntityProtection;

/**
 * EntityProtector
 * 
 * Protects entities by replacing them with placeholders before translation
 * and restoring them after translation
 */
class EntityProtector
{
    /**
     * Protect entities by replacing them with placeholders
     * 
     * @param string $text Original text
     * @param array $entities List of entities to protect
     * @return array ['text' => protected text, 'map' => placeholder mapping]
     */
    public function protect(string $text, array $entities): array
    {
        $map = [];
        $counter = 1;

        foreach ($entities as $entity) {
            $value = $entity['value'] ?? '';
            
            // Skip empty or very short values
            if ($value === '' || mb_strlen($value) < 2) {
                continue;
            }

            // Generate unique placeholder
            $placeholder = sprintf('{{ENT_%04d}}', $counter++);
            
            // Replace all occurrences (literal string replacement)
            $text = str_replace($value, $placeholder, $text);
            
            // Store mapping
            $map[$placeholder] = [
                'value' => $value,
                'type' => $entity['type'] ?? 'UNKNOWN',
                'confidence' => $entity['confidence'] ?? 'unknown'
            ];
        }

        return [
            'text' => $text,
            'map' => $map,
            'count' => count($map)
        ];
    }

    /**
     * Restore entities by replacing placeholders with original values
     * 
     * @param string $translatedText Translated text with placeholders
     * @param array $map Placeholder mapping from protect()
     * @return string Text with original entities restored
     */
    public function restore(string $translatedText, array $map): string
    {
        // Restore placeholders back to original entities
        foreach ($map as $placeholder => $entity) {
            $originalValue = is_array($entity) ? $entity['value'] : $entity;
            $translatedText = str_replace($placeholder, $originalValue, $translatedText);
        }

        return $translatedText;
    }

    /**
     * Enforce entity preservation by checking and restoring any modified entities
     * 
     * @param string $sourceText Original source text
     * @param string $translatedText Translated text
     * @param array $map Placeholder mapping
     * @return array ['text' => enforced text, 'violations' => list of violations]
     */
    public function enforce(string $sourceText, string $translatedText, array $map): array
    {
        $violations = [];
        $enforcedText = $translatedText;

        // Check each entity
        foreach ($map as $placeholder => $entity) {
            $originalValue = is_array($entity) ? $entity['value'] : $entity;
            $entityType = is_array($entity) ? ($entity['type'] ?? 'UNKNOWN') : 'UNKNOWN';
            
            // Check if entity exists in source
            $inSource = mb_strpos($sourceText, $originalValue) !== false;
            
            // Check if entity exists in translated text
            $inTranslated = mb_strpos($enforcedText, $originalValue) !== false;
            
            // If entity was in source but not in translated, it's a violation
            if ($inSource && !$inTranslated) {
                $violations[] = [
                    'type' => $entityType,
                    'value' => $originalValue,
                    'placeholder' => $placeholder,
                    'severity' => 'critical'
                ];
                
                // Try to restore it (if placeholder still exists)
                if (mb_strpos($enforcedText, $placeholder) !== false) {
                    $enforcedText = str_replace($placeholder, $originalValue, $enforcedText);
                }
            }
        }

        return [
            'text' => $enforcedText,
            'violations' => $violations,
            'is_valid' => empty($violations)
        ];
    }

    /**
     * Verify that all entities are preserved correctly
     * 
     * @param string $sourceText Original source text
     * @param string $translatedText Translated text
     * @param array $entities Original entities list
     * @return array Verification report
     */
    public function verify(string $sourceText, string $translatedText, array $entities): array
    {
        $report = [
            'total_entities' => count($entities),
            'preserved' => 0,
            'missing' => 0,
            'modified' => 0,
            'details' => []
        ];

        foreach ($entities as $entity) {
            $value = $entity['value'] ?? '';
            if ($value === '') continue;

            $inSource = mb_strpos($sourceText, $value) !== false;
            $inTranslated = mb_strpos($translatedText, $value) !== false;

            if ($inSource && $inTranslated) {
                $report['preserved']++;
                $report['details'][] = [
                    'value' => $value,
                    'type' => $entity['type'] ?? 'UNKNOWN',
                    'status' => 'preserved'
                ];
            } elseif ($inSource && !$inTranslated) {
                $report['missing']++;
                $report['details'][] = [
                    'value' => $value,
                    'type' => $entity['type'] ?? 'UNKNOWN',
                    'status' => 'missing',
                    'severity' => 'critical'
                ];
            }
        }

        $report['success_rate'] = $report['total_entities'] > 0 
            ? round(($report['preserved'] / $report['total_entities']) * 100, 2)
            : 100;

        return $report;
    }

    /**
     * Get statistics about protected entities
     * 
     * @param array $map Placeholder mapping
     * @return array Statistics
     */
    public function getStatistics(array $map): array
    {
        $stats = [
            'total' => count($map),
            'by_type' => [],
            'by_confidence' => []
        ];

        foreach ($map as $placeholder => $entity) {
            $type = is_array($entity) ? ($entity['type'] ?? 'UNKNOWN') : 'UNKNOWN';
            $confidence = is_array($entity) ? ($entity['confidence'] ?? 'unknown') : 'unknown';

            $stats['by_type'][$type] = ($stats['by_type'][$type] ?? 0) + 1;
            $stats['by_confidence'][$confidence] = ($stats['by_confidence'][$confidence] ?? 0) + 1;
        }

        return $stats;
    }
}
