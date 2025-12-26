<?php

namespace App\Services\PromptEngine;

class PromptComposer
{
    /**
     * Compose final prompt by injecting meta instructions (certified mode, output mode, entity preservation).
     */
    public function compose(string $prompt, array $meta): string
    {
        $certified = (bool)($meta['certified'] ?? false);
        $outputMode = $meta['output_mode'] ?? 'translation_only';

        $extras = [];

        // ========================================
        // ENTITY PRESERVATION LAYER (CRITICAL)
        // ========================================
        if ($certified) {
            $extras[] = $this->getEntityPreservationRule();
            $extras[] = "CERTIFICATION_MODE: This translation is intended for certified official use. Keep a formal tone and avoid creative paraphrasing.";
        }

        // Output mode
        if ($outputMode === 'translation_only') {
            $extras[] = "OUTPUT_MODE: Return translation only. No commentary.";
        } elseif ($outputMode === 'translation_with_notes') {
            $extras[] = "OUTPUT_MODE: Return translation first, then minimal notes if needed (max 5 bullets).";
        }

        // RTL/LTR handling helper
        if (strtolower($meta['target_language'] ?? '') === 'ar') {
            $extras[] = "RTL_RULE: Arabic text is RTL; keep numbers/codes/IBAN/URLs LTR exactly as-is.";
        }

        // Post-Translation Guard
        if ($certified) {
            $extras[] = $this->getPostTranslationGuard();
        }

        return trim($prompt . "\n\n" . implode("\n\n", $extras));
    }

    /**
     * Entity Preservation Rule (P-NAME-000)
     * CRITICAL: Prevents translation of protected entities
     */
    protected function getEntityPreservationRule(): string
    {
        return <<<'RULE'
⚠️ ENTITY PRESERVATION RULE (CRITICAL - NON-NEGOTIABLE):

Do NOT translate, transliterate, localize, adapt, or alter ANY of the following:

✗ Personal names (full names, first names, last names, middle names)
✗ Company names and legal entities (including B.V., N.V., GmbH, Ltd., Inc., LLC, etc.)
✗ Institution names (hospitals, clinics, schools, universities, government bodies)
✗ Brand names and trademarks
✗ Addresses (street names, cities, postal codes, countries)
✗ Identification numbers (passport, ID, driver's license, social security, BSN, etc.)
✗ Financial identifiers (IBAN, BIC, SWIFT, account numbers, payment references)
✗ Tax identifiers (VAT, BTW, KvK, AGB, Chamber of Commerce numbers)
✗ Registration codes and certificate IDs
✗ Case numbers, invoice numbers, reference numbers
✗ Court names and legal bodies
✗ Healthcare provider names and insurer names
✗ URLs, email addresses, QR codes
✗ Serial numbers, barcodes, product codes
✗ Acronyms and abbreviations (unless explicitly translatable)

These elements MUST remain EXACTLY as in the source text, character by character, including:
- Original spelling
- Capitalization
- Spacing
- Punctuation
- Legal suffixes

❗ Any violation of this rule invalidates the translation for official/certified use.
❗ When in doubt, DO NOT translate.
RULE;
    }

    /**
     * Post-Translation Guard (P-NAME-020)
     * Automatic verification step
     */
    protected function getPostTranslationGuard(): string
    {
        return <<<'GUARD'
🔒 POST-TRANSLATION VERIFICATION (AUTOMATIC):

Before finalizing the translation:
1. Compare the source and translated text
2. Verify that ALL protected entities remain unchanged
3. If any non-translatable entity was modified, revert it to the original
4. Do NOT mention this verification step in the output
5. Ensure the translation is ready for official/certified use

This is an internal quality check. Proceed silently.
GUARD;
    }
}
