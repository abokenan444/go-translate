<?php

namespace App\Services;

class SimpleCulturalAdapter
{
    /**
     * Build a simple cultural prompt without database dependencies
     */
    public function buildPrompt(
        string $text,
        string $sourceLang,
        string $targetLang,
        string $style = 'professional',
        ?string $context = null
    ): string {
        $culturalContext = $this->getCulturalContext($targetLang);
        $toneGuidance = $this->getToneGuidance($style);
        
        $prompt = "You are CulturalTranslate, an expert in cultural adaptation and translation.\n\n";
        $prompt .= "Your task is to translate the following text from {$sourceLang} to {$targetLang}, ";
        $prompt .= "while adapting it culturally to resonate with the target audience.\n\n";
        
        $prompt .= "Target Culture: {$culturalContext}\n";
        $prompt .= "Tone: {$toneGuidance}\n\n";
        
        if ($context) {
            $prompt .= "Context: {$context}\n\n";
        }
        
        $prompt .= "Important Guidelines:\n";
        $prompt .= "- Adapt idioms, expressions, and cultural references\n";
        $prompt .= "- Maintain the original intent and business goal\n";
        $prompt .= "- Use natural, native-sounding language\n";
        $prompt .= "- Consider cultural sensitivities and values\n";
        $prompt .= "- Avoid literal word-for-word translation\n\n";
        
        $prompt .= "Text to translate:\n{$text}\n\n";
        $prompt .= "Provide only the translated text without explanations.";
        
        return $prompt;
    }
    
    private function getCulturalContext(string $lang): string
    {
        $contexts = [
            'ar' => 'Arabic/Middle Eastern culture - formal, respectful, context-sensitive communication',
            'en' => 'English/Western culture - direct, casual-friendly, individualistic',
            'es' => 'Spanish/Latin American culture - warm, expressive, relationship-focused',
            'fr' => 'French/European culture - formal, elegant, sophisticated',
            'de' => 'German/European culture - precise, formal, efficiency-focused',
            'zh' => 'Chinese/Asian culture - respectful, hierarchical, indirect communication',
            'ja' => 'Japanese/Asian culture - polite, indirect, harmony-focused',
        ];
        
        return $contexts[$lang] ?? 'Global/International audience';
    }
    
    private function getToneGuidance(string $style): string
    {
        $tones = [
            'professional' => 'Professional and business-appropriate',
            'casual' => 'Casual and friendly',
            'formal' => 'Formal and respectful',
            'friendly' => 'Warm and approachable',
            'technical' => 'Technical and precise',
            'marketing' => 'Persuasive and engaging',
            'creative' => 'Creative and expressive',
        ];
        
        return $tones[$style] ?? 'Professional';
    }
}
