<?php

namespace App\Services\Notifications\Push;

use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FCMPushService
{
    protected string $serverKey;
    protected string $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
    
    public function __construct()
    {
        $this->serverKey = config('services.fcm.server_key', env('FCM_SERVER_KEY'));
    }
    
    /**
     * Send push notification to user's devices
     *
     * @param User $user
     * @param string $title
     * @param string $body
     * @param array $data Additional data payload
     * @return array
     */
    public function sendToUser(User $user, string $title, string $body, array $data = []): array
    {
        if (!$this->serverKey) {
            Log::warning('FCM server key not configured');
            return ['success' => false, 'reason' => 'fcm_not_configured'];
        }
        
        $tokens = DeviceToken::where('user_id', $user->id)
            ->where('last_seen_at', '>', now()->subDays(30)) // Only active devices
            ->pluck('token')
            ->toArray();
        
        if (empty($tokens)) {
            return ['success' => false, 'reason' => 'no_device_tokens'];
        }
        
        $results = [];
        
        foreach ($tokens as $token) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'key=' . $this->serverKey,
                    'Content-Type' => 'application/json',
                ])->post($this->fcmUrl, [
                    'to' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                        'sound' => 'default',
                        'badge' => '1',
                    ],
                    'data' => array_merge($data, [
                        'timestamp' => now()->toIso8601String(),
                    ]),
                    'priority' => 'high',
                ]);
                
                if ($response->successful()) {
                    $results[] = ['token' => substr($token, 0, 20) . '...', 'status' => 'sent'];
                } else {
                    $results[] = ['token' => substr($token, 0, 20) . '...', 'status' => 'failed', 'error' => $response->body()];
                    
                    // If token is invalid, remove it
                    if ($response->status() === 400 || $response->status() === 404) {
                        DeviceToken::where('token', $token)->delete();
                        Log::info('Removed invalid FCM token', ['token' => substr($token, 0, 20) . '...']);
                    }
                }
            } catch (\Exception $e) {
                Log::error('FCM push failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
                $results[] = ['token' => substr($token, 0, 20) . '...', 'status' => 'exception', 'error' => $e->getMessage()];
            }
        }
        
        return [
            'success' => true,
            'total_tokens' => count($tokens),
            'results' => $results,
        ];
    }
    
    /**
     * Send push notification to multiple users
     *
     * @param array $userIds
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     */
    public function sendToUsers(array $userIds, string $title, string $body, array $data = []): array
    {
        $results = [];
        
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $results[$userId] = $this->sendToUser($user, $title, $body, $data);
            }
        }
        
        return $results;
    }
    
    /**
     * Send to a topic (broadcast)
     *
     * @param string $topic
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     */
    public function sendToTopic(string $topic, string $title, string $body, array $data = []): array
    {
        if (!$this->serverKey) {
            Log::warning('FCM server key not configured');
            return ['success' => false, 'reason' => 'fcm_not_configured'];
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'key=' . $this->serverKey,
                'Content-Type' => 'application/json',
            ])->post($this->fcmUrl, [
                'to' => '/topics/' . $topic,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'sound' => 'default',
                ],
                'data' => array_merge($data, [
                    'timestamp' => now()->toIso8601String(),
                ]),
                'priority' => 'high',
            ]);
            
            return [
                'success' => $response->successful(),
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('FCM topic push failed', [
                'topic' => $topic,
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
