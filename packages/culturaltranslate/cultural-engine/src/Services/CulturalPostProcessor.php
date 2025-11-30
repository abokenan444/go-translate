<?php

namespace CulturalTranslate\CulturalEngine\Services;

class CulturalPostProcessor
{
    public function __construct(
        protected array $config = []
    ) {}

    public function cleanOutput(string $text): string
    {
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);
        return trim($text);
    }
}
