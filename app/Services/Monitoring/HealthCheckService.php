<?php

namespace App\Services\Monitoring;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Exception;

class HealthCheckService
{
    protected $results = [];
    protected $errors = [];
    protected $warnings = [];

    /**
     * تشغيل فحص صحي شامل للمنصة
     */
    public function runFullCheck(): array
    {
        $this->results = [];
        $this->errors = [];
        $this->warnings = [];

        // فحص قاعدة البيانات
        $this->checkDatabase();

        // فحص Redis
        $this->checkRedis();

        // فحص OpenAI API
        $this->checkOpenAI();

        // فحص Stripe API
        $this->checkStripe();

        // فحص File Storage
        $this->checkStorage();

        // فحص الصلاحيات
        $this->checkPermissions();

        // فحص Environment Variables
        $this->checkEnvironment();

        return [
            'status' => $this->getOverallStatus(),
            'results' => $this->results,
            'errors' => $this->errors,
            'warnings' => $this->warnings,
            'timestamp' => now()->toDateTimeString(),
            'summary' => $this->generateSummary(),
        ];
    }

    /**
     * فحص قاعدة البيانات
     */
    protected function checkDatabase(): void
    {
        try {
            DB::connection()->getPdo();
            
            // فحص الجداول الرئيسية
            $tables = ['users', 'translations', 'official_documents', 'subscriptions'];
            $missingTables = [];
            
            foreach ($tables as $table) {
                if (!DB::getSchemaBuilder()->hasTable($table)) {
                    $missingTables[] = $table;
                }
            }

            if (empty($missingTables)) {
                $this->results['database'] = [
                    'status' => 'OK',
                    'message' => 'Database connection successful',
                    'driver' => config('database.default'),
                    'tables_checked' => count($tables),
                ];
            } else {
                $this->errors[] = [
                    'service' => 'database',
                    'severity' => 'HIGH',
                    'message' => 'Missing tables: ' . implode(', ', $missingTables),
                ];
                $this->results['database'] = [
                    'status' => 'ERROR',
                    'message' => 'Missing critical tables',
                    'missing_tables' => $missingTables,
                ];
            }
        } catch (Exception $e) {
            $this->errors[] = [
                'service' => 'database',
                'severity' => 'CRITICAL',
                'message' => 'Database connection failed: ' . $e->getMessage(),
            ];
            $this->results['database'] = [
                'status' => 'CRITICAL',
                'message' => 'Database connection failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * فحص Redis
     */
    protected function checkRedis(): void
    {
        try {
            Redis::ping();
            
            // فحص القراءة والكتابة
            $testKey = 'health_check_test_' . time();
            Redis::set($testKey, 'test_value', 'EX', 10);
            $value = Redis::get($testKey);
            Redis::del($testKey);

            if ($value === 'test_value') {
                $this->results['redis'] = [
                    'status' => 'OK',
                    'message' => 'Redis connection and operations successful',
                ];
            } else {
                $this->warnings[] = [
                    'service' => 'redis',
                    'severity' => 'MEDIUM',
                    'message' => 'Redis read/write test failed',
                ];
                $this->results['redis'] = [
                    'status' => 'WARNING',
                    'message' => 'Redis operations not working correctly',
                ];
            }
        } catch (Exception $e) {
            $this->errors[] = [
                'service' => 'redis',
                'severity' => 'HIGH',
                'message' => 'Redis connection failed: ' . $e->getMessage(),
            ];
            $this->results['redis'] = [
                'status' => 'ERROR',
                'message' => 'Redis connection failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * فحص OpenAI API
     */
    protected function checkOpenAI(): void
    {
        try {
            $apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');
            
            if (empty($apiKey)) {
                $this->errors[] = [
                    'service' => 'openai',
                    'severity' => 'CRITICAL',
                    'message' => 'OpenAI API key not configured',
                ];
                $this->results['openai'] = [
                    'status' => 'ERROR',
                    'message' => 'API key missing',
                ];
                return;
            }

            // فحص بسيط للـ API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->timeout(5)->get('https://api.openai.com/v1/models');

            if ($response->successful()) {
                $this->results['openai'] = [
                    'status' => 'OK',
                    'message' => 'OpenAI API connection successful',
                ];
            } else {
                $this->errors[] = [
                    'service' => 'openai',
                    'severity' => 'HIGH',
                    'message' => 'OpenAI API returned error: ' . $response->status(),
                ];
                $this->results['openai'] = [
                    'status' => 'ERROR',
                    'message' => 'API request failed',
                    'status_code' => $response->status(),
                ];
            }
        } catch (Exception $e) {
            $this->errors[] = [
                'service' => 'openai',
                'severity' => 'HIGH',
                'message' => 'OpenAI API check failed: ' . $e->getMessage(),
            ];
            $this->results['openai'] = [
                'status' => 'ERROR',
                'message' => 'API check failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * فحص Stripe API
     */
    protected function checkStripe(): void
    {
        try {
            $apiKey = config('services.stripe.secret');
            
            if (empty($apiKey)) {
                $this->warnings[] = [
                    'service' => 'stripe',
                    'severity' => 'MEDIUM',
                    'message' => 'Stripe API key not configured',
                ];
                $this->results['stripe'] = [
                    'status' => 'WARNING',
                    'message' => 'API key missing',
                ];
                return;
            }

            // فحص بسيط للـ API
            $response = Http::withBasicAuth($apiKey, '')
                ->timeout(5)
                ->get('https://api.stripe.com/v1/balance');

            if ($response->successful()) {
                $this->results['stripe'] = [
                    'status' => 'OK',
                    'message' => 'Stripe API connection successful',
                ];
            } else {
                $this->errors[] = [
                    'service' => 'stripe',
                    'severity' => 'MEDIUM',
                    'message' => 'Stripe API returned error: ' . $response->status(),
                ];
                $this->results['stripe'] = [
                    'status' => 'ERROR',
                    'message' => 'API request failed',
                    'status_code' => $response->status(),
                ];
            }
        } catch (Exception $e) {
            $this->warnings[] = [
                'service' => 'stripe',
                'severity' => 'MEDIUM',
                'message' => 'Stripe API check failed: ' . $e->getMessage(),
            ];
            $this->results['stripe'] = [
                'status' => 'WARNING',
                'message' => 'API check failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * فحص File Storage
     */
    protected function checkStorage(): void
    {
        try {
            $testFile = 'health_check_test_' . time() . '.txt';
            Storage::put($testFile, 'test content');
            
            if (Storage::exists($testFile)) {
                $content = Storage::get($testFile);
                Storage::delete($testFile);
                
                if ($content === 'test content') {
                    $this->results['storage'] = [
                        'status' => 'OK',
                        'message' => 'File storage read/write successful',
                    ];
                } else {
                    $this->warnings[] = [
                        'service' => 'storage',
                        'severity' => 'MEDIUM',
                        'message' => 'File storage read failed',
                    ];
                    $this->results['storage'] = [
                        'status' => 'WARNING',
                        'message' => 'Storage operations not working correctly',
                    ];
                }
            } else {
                $this->errors[] = [
                    'service' => 'storage',
                    'severity' => 'HIGH',
                    'message' => 'File storage write failed',
                ];
                $this->results['storage'] = [
                    'status' => 'ERROR',
                    'message' => 'Cannot write to storage',
                ];
            }
        } catch (Exception $e) {
            $this->errors[] = [
                'service' => 'storage',
                'severity' => 'HIGH',
                'message' => 'Storage check failed: ' . $e->getMessage(),
            ];
            $this->results['storage'] = [
                'status' => 'ERROR',
                'message' => 'Storage check failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * فحص الصلاحيات
     */
    protected function checkPermissions(): void
    {
        $criticalPaths = [
            storage_path(),
            storage_path('logs'),
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            base_path('bootstrap/cache'),
        ];

        $permissionIssues = [];

        foreach ($criticalPaths as $path) {
            if (!is_writable($path)) {
                $permissionIssues[] = $path;
            }
        }

        if (empty($permissionIssues)) {
            $this->results['permissions'] = [
                'status' => 'OK',
                'message' => 'All critical paths are writable',
                'paths_checked' => count($criticalPaths),
            ];
        } else {
            $this->errors[] = [
                'service' => 'permissions',
                'severity' => 'HIGH',
                'message' => 'Some paths are not writable: ' . implode(', ', $permissionIssues),
            ];
            $this->results['permissions'] = [
                'status' => 'ERROR',
                'message' => 'Permission issues detected',
                'non_writable_paths' => $permissionIssues,
            ];
        }
    }

    /**
     * فحص Environment Variables
     */
    protected function checkEnvironment(): void
    {
        $requiredVars = [
            'APP_KEY',
            'APP_URL',
            'DB_CONNECTION',
            'REDIS_HOST',
            'OPENAI_API_KEY',
        ];

        $missingVars = [];

        foreach ($requiredVars as $var) {
            if (empty(env($var))) {
                $missingVars[] = $var;
            }
        }

        if (empty($missingVars)) {
            $this->results['environment'] = [
                'status' => 'OK',
                'message' => 'All required environment variables are set',
                'vars_checked' => count($requiredVars),
            ];
        } else {
            $this->errors[] = [
                'service' => 'environment',
                'severity' => 'CRITICAL',
                'message' => 'Missing environment variables: ' . implode(', ', $missingVars),
            ];
            $this->results['environment'] = [
                'status' => 'ERROR',
                'message' => 'Missing critical environment variables',
                'missing_vars' => $missingVars,
            ];
        }
    }

    /**
     * الحصول على الحالة العامة
     */
    protected function getOverallStatus(): string
    {
        foreach ($this->results as $result) {
            if ($result['status'] === 'CRITICAL' || $result['status'] === 'ERROR') {
                return 'ERROR';
            }
        }

        foreach ($this->results as $result) {
            if ($result['status'] === 'WARNING') {
                return 'WARNING';
            }
        }

        return 'OK';
    }

    /**
     * إنشاء ملخص
     */
    protected function generateSummary(): array
    {
        $total = count($this->results);
        $ok = 0;
        $warning = 0;
        $error = 0;

        foreach ($this->results as $result) {
            switch ($result['status']) {
                case 'OK':
                    $ok++;
                    break;
                case 'WARNING':
                    $warning++;
                    break;
                case 'ERROR':
                case 'CRITICAL':
                    $error++;
                    break;
            }
        }

        return [
            'total_checks' => $total,
            'passed' => $ok,
            'warnings' => $warning,
            'errors' => $error,
            'health_percentage' => $total > 0 ? round(($ok / $total) * 100, 2) : 0,
        ];
    }
}
