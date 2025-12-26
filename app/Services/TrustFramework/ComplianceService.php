<?php
namespace App\Services\TrustFramework;
class ComplianceService
{
    protected array $availableSeals = [
        'GDPR' => ['name' => 'EU GDPR Compliant', 'description' => 'Complies with EU General Data Protection Regulation', 'color' => '#0052CC'],
        'ISO27001' => ['name' => 'ISO 27001 Ready', 'description' => 'Information Security Management System', 'color' => '#E74C3C'],
        'EN15038' => ['name' => 'EN 15038 Certified', 'description' => 'European Standard for Translation Services', 'color' => '#27AE60'],
        'ISO17100' => ['name' => 'ISO 17100 Certified', 'description' => 'Translation Services Requirements', 'color' => '#8E44AD'],
        'SWORN' => ['name' => 'Sworn Translation', 'description' => 'Certified by Official Sworn Translator', 'color' => '#C0392B'],
    ];
    
    public function getAvailableSeals(): array { return $this->availableSeals; }
    public function getSeal(string $code): ?array { return $this->availableSeals[$code] ?? null; }
    
    public function getSealsForCertificate(array $data): array
    {
        $seals = ['GDPR', 'ISO27001'];
        if ($data['sworn_translation'] ?? false) {
            $seals[] = 'SWORN';
            $seals[] = 'EN15038';
            $seals[] = 'ISO17100';
        } else {
            $seals[] = 'EN15038';
        }
        return array_unique($seals);
    }
}
