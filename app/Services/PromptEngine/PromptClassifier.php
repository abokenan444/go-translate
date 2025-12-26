<?php

namespace App\Services\PromptEngine;

class PromptClassifier
{
    /**
     * Infer domain using document_type and simple heuristics.
     * You can later replace this with ML/LLM classification.
     */
    public function inferDomain(array $meta): string
    {
        $docType = strtolower($meta['document_type'] ?? '');
        $filename = strtolower($meta['filename'] ?? '');

        $financial = ['invoice','utility_bill','bank_statement','tax_notice','payslip','customs_invoice','shipping_doc'];
        $medical = ['medical_invoice','lab_results','medical_report','radiology_report'];
        $legal = ['contract','terms_conditions','privacy_policy','court_judgment','power_of_attorney','employment_contract'];
        $official = ['birth_certificate','marriage_certificate','death_certificate','residence_permit','passport'];
        $academic = ['transcript','diploma','recommendation_letter'];
        $tech = ['api_docs','app_ui','manual','spec','documentation'];
        $marketing = ['website','landing_page','ad_copy','seo'];

        foreach ($financial as $k) if (str_contains($docType, $k) || str_contains($filename, $k)) return 'financial';
        foreach ($medical as $k) if (str_contains($docType, $k) || str_contains($filename, $k)) return 'medical';
        foreach ($legal as $k) if (str_contains($docType, $k) || str_contains($filename, $k)) return 'legal';
        foreach ($official as $k) if (str_contains($docType, $k) || str_contains($filename, $k)) return 'official';
        foreach ($academic as $k) if (str_contains($docType, $k) || str_contains($filename, $k)) return 'academic';
        foreach ($tech as $k) if (str_contains($docType, $k) || str_contains($filename, $k)) return 'tech';
        foreach ($marketing as $k) if (str_contains($docType, $k) || str_contains($filename, $k)) return 'marketing';

        return 'general';
    }
}
