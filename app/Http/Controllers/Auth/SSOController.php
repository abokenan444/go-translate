<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SSOService;
use Illuminate\Http\Request;

class SSOController extends Controller
{
    protected $ssoService;

    public function __construct(SSOService $ssoService)
    {
        $this->ssoService = $ssoService;
    }

    public function login(Request $request, string $provider)
    {
        // Simulate redirect to IdP (Okta, Azure AD, etc.)
        $redirectUrl = "https://idp.example.com/sso/authorize?client_id=cultural_translate&provider={$provider}";
        
        if ($request->wantsJson()) {
            return response()->json(['redirect_url' => $redirectUrl]);
        }

        return redirect($redirectUrl);
    }

    public function callback(Request $request, string $provider)
    {
        // Simulate receiving a callback from IdP
        // In reality, we would parse the SAMLResponse or OIDC code
        
        try {
            // Mock payload for demonstration
            $payload = [
                'email' => 'enterprise.admin@fortune500.com',
                'name' => 'Enterprise Admin',
                'sub' => 'user_123456',
                'groups' => ['admin', 'translator']
            ];

            $user = $this->ssoService->handleLogin($provider, $payload);

            return redirect('/admin')->with('success', "Logged in via {$provider}");
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'SSO Login Failed: ' . $e->getMessage());
        }
    }

    public function metadata()
    {
        return response($this->ssoService->getMetadata())
            ->header('Content-Type', 'application/xml');
    }
}
