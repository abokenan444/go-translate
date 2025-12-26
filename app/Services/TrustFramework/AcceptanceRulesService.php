<?php
namespace App\Services\TrustFramework;

use Illuminate\Support\Facades\Storage;

class AcceptanceRulesService
{
    protected array $rules;
    protected array $documentTypeToSector = [
        'birth_certificate' => 'government',
        'marriage_certificate' => 'government',
        'divorce_decree' => 'legal',
        'court_order' => 'legal',
        'medical_report' => 'medical',
        'prescription' => 'medical',
        'diploma' => 'education',
        'transcript' => 'education',
        'employment_contract' => 'employment',
        'bank_statement' => 'banking',
        'financial_statement' => 'banking',
    ];
    
    public function __construct()
    {
        $json = file_get_contents(storage_path('app/acceptance_rules.json'));
        $this->rules = json_decode($json, true);
    }
    
    public function getRules(string $country = null, string $sector = null, string $lang = 'en'): array
    {
        $result = [];
        
        // Always include global notice
        $result['global'] = $this->rules['global'][$lang] ?? $this->rules['global']['en'];
        
        // Add country-specific rules
        if ($country && isset($this->rules['countries'][$country])) {
            $countryRules = $this->rules['countries'][$country];
            $result['country'] = $countryRules[$lang] ?? $countryRules['en'];
            $result['country_name'] = $countryRules['name'];
        }
        
        // Add sector-specific rules
        if ($sector && isset($this->rules['sectors'][$sector])) {
            $result['sector'] = $this->rules['sectors'][$sector][$lang] ?? $this->rules['sectors'][$sector]['en'];
        }
        
        return $result;
    }
    
    public function getSectorFromDocumentType(string $documentType): ?string
    {
        return $this->documentTypeToSector[$documentType] ?? null;
    }
    
    public function getCountryFromIP(string $ip): ?string
    {
        // Simple IP-based country detection
        // In production, use a service like MaxMind GeoIP2
        
        // For now, return null (will use default)
        return null;
    }
    
    public function getAllCountries(): array
    {
        $countries = [];
        foreach ($this->rules['countries'] as $code => $data) {
            $countries[$code] = $data['name'];
        }
        return $countries;
    }
    
    public function getAllSectors(): array
    {
        $sectors = [];
        foreach ($this->rules['sectors'] as $code => $data) {
            $sectors[$code] = $data['en']['title'];
        }
        return $sectors;
    }
}
