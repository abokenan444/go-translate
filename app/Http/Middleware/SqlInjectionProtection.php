<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SecurityAlertNotification;
use App\Models\SecurityLog;
use App\Models\User;

class SqlInjectionProtection
{
    protected $patterns = [
        '/(\bUNION\b.*\bSELECT\b)/i',
        '/(\bSELECT\b.*\bFROM\b.*\bWHERE\b)/i',
        '/(\bINSERT\b.*\bINTO\b.*\bVALUES\b)/i',
        '/(\bUPDATE\b.*\bSET\b)/i',
        '/(\bDELETE\b.*\bFROM\b)/i',
        '/(\bDROP\b.*\bTABLE\b)/i',
        '/(\bEXEC\b|\bEXECUTE\b)/i',
        '/(\bSCRIPT\b.*\>)/i',
        '/(<\s*script)/i',
    ];

    public function handle(Request $request, Closure $next)
    {
        $inputs = $request->all();
        
        foreach ($inputs as $key => $value) {
            if (is_string($value) && $this->containsSqlInjection($value)) {
                $attackDetails = [
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                    'input' => $key,
                    'value' => $value,
                    'user_agent' => $request->userAgent(),
                    'referer' => $request->header('referer'),
                    'method' => $request->method(),
                ];

                // Log the attack
                Log::warning('SQL Injection attempt detected', $attackDetails);
                
                // Save to database
                try {
                    SecurityLog::create([
                        'attack_type' => 'SQL Injection',
                        'ip_address' => $attackDetails['ip'],
                        'url' => $attackDetails['url'],
                        'input_field' => $attackDetails['input'],
                        'suspicious_value' => substr($attackDetails['value'], 0, 500),
                        'user_agent' => $attackDetails['user_agent'],
                        'referer' => $attackDetails['referer'],
                        'request_method' => $attackDetails['method'],
                        'payload' => $request->all(),
                        'severity' => 'high',
                        'blocked' => true,
                        'notified_at' => now(),
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to save security log: ' . $e->getMessage());
                }

                // Send notification to all super admins
                try {
                    $admins = User::where('is_super_admin', true)
                        ->orWhereHas('roles', function($query) {
                            $query->where('name', 'super_admin');
                        })
                        ->get();

                    if ($admins->count() > 0) {
                        Notification::send($admins, new SecurityAlertNotification('SQL Injection', $attackDetails));
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to send security notification: ' . $e->getMessage());
                }
                
                abort(403, 'Forbidden: Suspicious input detected');
            }
        }
        
        return $next($request);
    }
    
    protected function containsSqlInjection($value)
    {
        foreach ($this->patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }
        
        return false;
    }
}
