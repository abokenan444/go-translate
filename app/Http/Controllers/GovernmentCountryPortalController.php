<?php

namespace App\Http\Controllers;

use App\Models\GovernmentPortal;
use App\Models\OfficialDocument;
use App\Services\JurisdictionService;
use Illuminate\Http\Request;

class GovernmentCountryPortalController extends Controller
{
    public function __construct(
        private JurisdictionService $jurisdictionService
    ) {}

    /**
     * Show list of all government portals
     */
    public function directory()
    {
        $portals = GovernmentPortal::where('is_active', true)
            ->orderBy('country_name')
            ->get()
            ->groupBy(function ($portal) {
                return $this->getRegion($portal->country_code);
            });

        return view('government.directory', compact('portals'));
    }

    /**
     * Show the government portal home page for a specific country
     */
    public function portalIndex(Request $request, string $country)
    {
        $portal = $this->getPortal($country);
        
        if (!$portal) {
            abort(404, 'Government portal not found for this country.');
        }

        return view('government.portal.index', compact('portal'));
    }

    /**
     * Show document submission form
     */
    public function showSubmitForm(Request $request, string $country)
    {
        $portal = $this->getPortal($country);
        
        if (!$portal) {
            abort(404, 'Government portal not found for this country.');
        }

        return view('government.portal.submit', compact('portal'));
    }

    /**
     * Handle document submission
     */
    public function submitDocument(Request $request, string $country)
    {
        $portal = $this->getPortal($country);
        
        if (!$portal) {
            abort(404);
        }

        $validated = $request->validate([
            'document_type' => 'required|string|in:certificate,legal,official,immigration,academic',
            'source_language' => 'required|string|size:2',
            'target_language' => 'required|string|size:2',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:20480',
            'country_selected_by_user' => 'nullable|string|size:2',
            'urgent' => 'boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Validate country consistency
        $userCountry = $validated['country_selected_by_user'] ?? null;
        if (!$this->jurisdictionService->validateCountryConsistency(
            $userCountry, 
            $portal->country_code, 
            'government'
        )) {
            return back()->withErrors([
                'country_selected_by_user' => 'Selected country must match the portal jurisdiction (' . $portal->country_code . ').'
            ])->withInput();
        }

        // Store the document
        $file = $request->file('document');
        $path = $file->store('government-documents/' . $portal->country_code, 'private');

        // Create official document
        $document = OfficialDocument::create([
            'user_id' => auth()->id(),
            'document_type' => $validated['document_type'],
            'certification_type' => 'government',
            'source_language' => $validated['source_language'],
            'target_language' => $validated['target_language'],
            'source_lang' => $validated['source_language'],
            'target_lang' => $validated['target_language'],
            'country_selected_by_user' => $userCountry,
            'country_from_portal' => $portal->country_code,
            'jurisdiction_country' => $portal->country_code,
            'original_filename' => $file->getClientOriginalName(),
            'stored_filename' => basename($path),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'status' => 'pending',
            'assignment_status' => 'pending',
            'priority_level' => $validated['urgent'] ?? false ? 'urgent' : 'normal',
        ]);

        // Finalize jurisdiction
        $this->jurisdictionService->finalizeJurisdictionCountry($document);

        // Dispatch assignment job
        if (class_exists(\App\Jobs\OfferAssignmentsJob::class)) {
            \App\Jobs\OfferAssignmentsJob::dispatch($document->id, 'official_documents');
        }

        return redirect()
            ->route('gov.country.track', ['country' => $country, 'reference' => $document->id])
            ->with('success', 'Document submitted successfully. Reference: #' . $document->id);
    }

    /**
     * Track document status
     */
    public function trackDocument(Request $request, string $country, string $reference)
    {
        $portal = $this->getPortal($country);
        
        $document = OfficialDocument::where('id', $reference)
            ->where('jurisdiction_country', $portal?->country_code)
            ->firstOrFail();

        return view('government.portal.track', compact('portal', 'document'));
    }

    /**
     * Verify document certificate
     */
    public function verifyDocument(Request $request, string $country, string $certificate)
    {
        $portal = $this->getPortal($country);

        $document = OfficialDocument::where('certificate_id', $certificate)
            ->firstOrFail();

        return view('government.portal.verify', compact('portal', 'document'));
    }

    /**
     * Show portal requirements
     */
    public function requirements(Request $request, string $country)
    {
        $portal = $this->getPortal($country);
        
        return view('government.portal.requirements', compact('portal'));
    }

    /**
     * Show portal pricing
     */
    public function pricing(Request $request, string $country)
    {
        $portal = $this->getPortal($country);
        
        return view('government.portal.pricing', compact('portal'));
    }

    /**
     * API: Get price estimate
     */
    public function getEstimate(Request $request, string $country)
    {
        $portal = $this->getPortal($country);

        $request->validate([
            'pages' => 'required|integer|min:1|max:1000',
            'document_type' => 'required|string',
            'urgent' => 'boolean',
        ]);

        $pages = $request->input('pages');
        $urgent = $request->input('urgent', false);

        // Base price per page
        $basePrice = 25.00;
        
        if ($portal?->requires_notarization) {
            $basePrice += 10.00;
        }
        if ($portal?->requires_apostille) {
            $basePrice += 15.00;
        }
        if ($urgent) {
            $basePrice *= 1.5;
        }

        $total = $basePrice * $pages;

        return response()->json([
            'pages' => $pages,
            'price_per_page' => $basePrice,
            'total' => round($total, 2),
            'currency' => $portal?->currency_code ?? 'USD',
            'estimated_days' => $urgent ? 1 : 3,
        ]);
    }

    /**
     * Get portal by country code
     */
    private function getPortal(string $country): ?GovernmentPortal
    {
        return GovernmentPortal::where('country_code', strtoupper($country))
            ->orWhere('portal_slug', strtolower($country))
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get region for grouping
     */
    private function getRegion(string $countryCode): string
    {
        $regions = [
            'Europe' => ['NL', 'DE', 'FR', 'GB', 'IT', 'ES', 'PT', 'BE', 'AT', 'CH', 'SE', 'NO', 'DK', 'FI', 'IE', 'PL', 'CZ', 'HU', 'GR', 'RO', 'BG', 'HR', 'SK', 'SI', 'LT', 'LV', 'EE', 'CY', 'MT', 'LU', 'IS', 'UA', 'RU', 'BY', 'MD', 'RS', 'BA', 'ME', 'MK', 'AL', 'XK'],
            'Middle East & North Africa' => ['AE', 'SA', 'QA', 'KW', 'BH', 'OM', 'JO', 'LB', 'IL', 'PS', 'EG', 'MA', 'DZ', 'TN', 'LY', 'IQ', 'SY', 'YE', 'IR', 'TR'],
            'Asia Pacific' => ['CN', 'JP', 'KR', 'KP', 'IN', 'PK', 'BD', 'LK', 'NP', 'TH', 'VN', 'MY', 'SG', 'ID', 'PH', 'MM', 'KH', 'LA', 'HK', 'TW', 'MN', 'KZ', 'UZ', 'TM', 'TJ', 'KG', 'AF', 'AU', 'NZ', 'FJ', 'PG'],
            'Americas' => ['US', 'CA', 'MX', 'BR', 'AR', 'CL', 'CO', 'PE', 'VE', 'EC', 'BO', 'PY', 'UY', 'PA', 'CR', 'GT', 'CU', 'DO', 'HN', 'SV', 'NI', 'HT', 'JM', 'TT'],
            'Africa' => ['ZA', 'NG', 'KE', 'GH', 'ET', 'TZ', 'UG', 'RW', 'SD', 'SN', 'CI', 'CM', 'AO', 'MZ', 'ZW', 'MU'],
        ];

        foreach ($regions as $region => $countries) {
            if (in_array($countryCode, $countries)) {
                return $region;
            }
        }

        return 'Other';
    }
}
