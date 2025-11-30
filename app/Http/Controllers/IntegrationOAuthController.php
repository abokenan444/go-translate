<?php

namespace App\Http\Controllers;

use App\Models\IntegrationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class IntegrationOAuthController extends Controller
{
    public function connect(Request $request, string $provider)
    {
        $provider = strtolower($provider);
        if (!in_array($provider, ['zoom','teams'])) {
            return response()->json(['success' => false, 'message' => 'Unsupported provider'], 422);
        }
        $state = Str::random(32);
        session(['oauth_state_'.$provider => $state]);

        if ($provider === 'zoom') {
            $cfg = config('services.zoom');
            $scopes = implode(' ', $cfg['scopes']);
            $url = $cfg['base_auth']."?response_type=code&client_id=".$cfg['client_id']."&redirect_uri=".urlencode($cfg['redirect'])."&scope=".urlencode($scopes)."&state=$state";
        } else {
            $cfg = config('services.teams');
            $scopes = implode(' ', $cfg['scopes']);
            $url = $cfg['auth']."?client_id=".$cfg['client_id']."&response_type=code&redirect_uri=".urlencode($cfg['redirect'])."&scope=".urlencode($scopes)."&state=$state";
        }
        return response()->json(['success' => true, 'authorize_url' => $url]);
    }

    public function callback(Request $request, string $provider)
    {
        $provider = strtolower($provider);
        $state = $request->get('state');
        $expected = session('oauth_state_'.$provider);
        if (!$expected || $state !== $expected) {
            return response()->json(['success'=>false,'message'=>'Invalid state'], 400);
        }
        $code = $request->get('code');
        if (!$code) {
            return response()->json(['success'=>false,'message'=>'Missing code'], 400);
        }

        try {
            if ($provider === 'zoom') {
                $cfg = config('services.zoom');
                $resp = Http::withBasicAuth($cfg['client_id'], $cfg['client_secret'])
                    ->asForm()
                    ->post($cfg['base_token'], [
                        'grant_type' => 'authorization_code',
                        'code' => $code,
                        'redirect_uri' => $cfg['redirect'],
                    ]);
            } else {
                $cfg = config('services.teams');
                $resp = Http::asForm()->post($cfg['token'], [
                    'client_id' => $cfg['client_id'],
                    'client_secret' => $cfg['client_secret'],
                    'code' => $code,
                    'redirect_uri' => $cfg['redirect'],
                    'grant_type' => 'authorization_code',
                ]);
            }
            if (!$resp->ok()) {
                Log::warning('OAuth token exchange failed', ['provider'=>$provider,'status'=>$resp->status(),'body'=>$resp->body()]);
                return response()->json(['success'=>false,'message'=>'Token exchange failed'], 500);
            }
            $data = $resp->json();
            $userId = Auth::id();
            $token = IntegrationToken::updateOrCreate([
                'user_id' => $userId,
                'provider' => $provider,
            ], [
                'access_token' => $data['access_token'] ?? null,
                'refresh_token' => $data['refresh_token'] ?? null,
                'expires_at' => isset($data['expires_in']) ? now()->addSeconds($data['expires_in']) : null,
                'scope' => $data['scope'] ?? null,
                'meta' => [ 'raw' => $data ],
            ]);
            return response()->json(['success'=>true,'provider'=>$provider,'expires_at'=>$token->expires_at]);
        } catch (\Throwable $e) {
            Log::error('OAuth callback error', ['provider'=>$provider,'error'=>$e->getMessage()]);
            return response()->json(['success'=>false,'message'=>'OAuth error'], 500);
        }
    }

    public function disconnect(Request $request, string $provider)
    {
        $provider = strtolower($provider);
        IntegrationToken::where('user_id', Auth::id())->where('provider', $provider)->delete();
        return response()->json(['success'=>true]);
    }
}
