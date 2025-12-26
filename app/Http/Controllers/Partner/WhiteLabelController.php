<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\PartnerWhiteLabel;
use Illuminate\Http\Request;

class WhiteLabelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $partner = auth()->user()->partner;
        
        if (!$partner->canUseWhiteLabel()) {
            return redirect()->route('partner.subscription')
                ->with('error', 'White Label feature requires Professional or Enterprise subscription');
        }
        
        $whiteLabel = $partner->whiteLabel ?? new PartnerWhiteLabel();
        
        return view('partners.white-label', compact('partner', 'whiteLabel'));
    }

    public function update(Request $request)
    {
        $partner = auth()->user()->partner;
        
        if (!$partner->canUseWhiteLabel()) {
            return back()->with('error', 'White Label feature not available');
        }

        $request->validate([
            'brand_name' => 'required|string|max:255',
            'primary_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'secondary_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'logo' => 'nullable|image|max:2048',
            'custom_domain' => 'nullable|string|max:255|unique:partner_white_labels,custom_domain,' . ($partner->whiteLabel->id ?? 'NULL'),
            'support_email' => 'nullable|email',
        ]);

        $data = $request->only([
            'brand_name', 'primary_color', 'secondary_color',
            'custom_css', 'email_from_name', 'email_from_address',
            'support_email', 'support_phone'
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('partner-logos', 'public');
            $data['logo_url'] = '/storage/' . $path;
        }

        // Handle custom domain
        if ($request->filled('custom_domain') && $partner->canUseCustomDomain()) {
            $data['custom_domain'] = $request->custom_domain;
            $data['domain_verified'] = false; // Needs verification
        }

        if ($partner->whiteLabel) {
            $partner->whiteLabel->update($data);
        } else {
            $data['partner_id'] = $partner->id;
            PartnerWhiteLabel::create($data);
        }

        return back()->with('success', 'White Label settings updated successfully');
    }

    public function verifyDomain()
    {
        $partner = auth()->user()->partner;
        
        if (!$partner->whiteLabel || !$partner->whiteLabel->custom_domain) {
            return back()->with('error', 'No custom domain configured');
        }

        // In production, verify DNS records here
        $partner->whiteLabel->verifyDomain();
        
        return back()->with('success', 'Domain verified successfully');
    }
}
