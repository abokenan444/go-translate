<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\IntegrationToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RefreshIntegrationTokens extends Command
{
    protected $signature = 'integrations:refresh-tokens';
    protected $description = 'Refresh Zoom/Teams OAuth tokens nearing or past expiry';

    public function handle(): int
    {
        $tokens = IntegrationToken::query()->whereNotNull('refresh_token')->get();
        $refreshed = 0; $failed = 0;
        foreach ($tokens as $token) {
            if (!$token->isExpired() && $token->expires_at && $token->expires_at->diffInMinutes(now()) > 15) {
                continue; // Skip tokens still valid for > 15 minutes
            }
            try {
                if ($token->provider === 'zoom') {
                    $cfg = config('services.zoom');
                    $resp = Http::withBasicAuth($cfg['client_id'], $cfg['client_secret'])
                        ->asForm()
                        ->post($cfg['base_token'], [
                            'grant_type' => 'refresh_token',
                            'refresh_token' => $token->refresh_token,
                        ]);
                } elseif ($token->provider === 'teams') {
                    $cfg = config('services.teams');
                    $resp = Http::asForm()->post($cfg['token'], [
                        'client_id' => $cfg['client_id'],
                        'client_secret' => $cfg['client_secret'],
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $token->refresh_token,
                        'scope' => implode(' ', $cfg['scopes']),
                        'redirect_uri' => $cfg['redirect'],
                    ]);
                } else {
                    continue;
                }
                if ($resp->ok()) {
                    $data = $resp->json();
                    $token->update([
                        'access_token' => $data['access_token'] ?? $token->access_token,
                        'refresh_token' => $data['refresh_token'] ?? $token->refresh_token,
                        'expires_at' => isset($data['expires_in']) ? now()->addSeconds($data['expires_in']) : $token->expires_at,
                        'scope' => $data['scope'] ?? $token->scope,
                    ]);
                    $refreshed++;
                } else {
                    $failed++;
                    Log::warning('Token refresh failed', ['provider'=>$token->provider,'status'=>$resp->status(),'body'=>$resp->body()]);
                }
            } catch (\Throwable $e) {
                $failed++;
                Log::error('Token refresh exception', ['provider'=>$token->provider,'error'=>$e->getMessage()]);
            }
        }
        $this->info("Refreshed: $refreshed | Failed: $failed");
        return self::SUCCESS;
    }
}
