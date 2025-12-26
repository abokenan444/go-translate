<?php

namespace App\Services\Monitoring;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Exception;

class ServiceIntegrityChecker
{
    protected $results = [];
    protected $errors = [];
    protected $warnings = [];
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('app.url');
    }

    /**
     * فحص شامل لجميع الخدمات الحيوية
     */
    public function checkAllServices(): array
    {
        $this->results = [];
        $this->errors = [];
        $this->warnings = [];

        // فحص خدمة الترجمة
        $this->checkTranslationService();

        // فحص خدمة الوثائق الرسمية
        $this->checkOfficialDocumentsService();

        // فحص خدمة الترجمة المعتمدة
        $this->checkCertifiedTranslationService();

        // فحص لوحة الشركاء
        $this->checkPartnerDashboard();

        // فحص نظام الأفلييت
        $this->checkAffiliateSystem();

        // فحص نظام الدفع
        $this->checkPaymentSystem();

        // فحص نظام المصادقة
        $this->checkAuthenticationSystem();

        // فحص الصفحات الرئيسية
        $this->checkMainPages();

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
     * فحص خدمة الترجمة
     */
    protected function checkTranslationService(): void
    {
        try {
            // فحص وجود جدول الترجمات
            if (!DB::getSchemaBuilder()->hasTable('translations')) {
                $this->errors[] = [
                    'service' => 'translation',
                    'severity' => 'CRITICAL',
                    'message' => 'Translations table does not exist',
                ];
                $this->results['translation_service'] = [
                    'status' => 'ERROR',
                    'message' => 'Database table missing',
                ];
                return;
            }

            // فحص OpenAI API key أو Translation API key
            $apiKey = config('services.openai.key') ?: config('services.translation_api.key');
            if (empty($apiKey)) {
                $this->errors[] = [
                    'service' => 'translation',
                    'severity' => 'CRITICAL',
                    'message' => 'OpenAI API key not configured',
                ];
                $this->results['translation_service'] = [
                    'status' => 'ERROR',
                    'message' => 'API key missing',
                ];
                return;
            }

            // فحص عدد الترجمات في قاعدة البيانات
            $translationCount = DB::table('translations')->count();

            $this->results['translation_service'] = [
                'status' => 'OK',
                'message' => 'Translation service is operational',
                'total_translations' => $translationCount,
            ];
        } catch (Exception $e) {
            $this->errors[] = [
                'service' => 'translation',
                'severity' => 'HIGH',
                'message' => 'Translation service check failed: ' . $e->getMessage(),
            ];
            $this->results['translation_service'] = [
                'status' => 'ERROR',
                'message' => 'Service check failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * فحص خدمة الوثائق الرسمية
     */
    protected function checkOfficialDocumentsService(): void
    {
        try {
            // فحص وجود جدول الوثائق
            if (!DB::getSchemaBuilder()->hasTable('official_documents')) {
                $this->errors[] = [
                    'service' => 'official_documents',
                    'severity' => 'HIGH',
                    'message' => 'Official documents table does not exist',
                ];
                $this->results['official_documents_service'] = [
                    'status' => 'ERROR',
                    'message' => 'Database table missing',
                ];
                return;
            }

            // فحص مجلد التخزين
            $storagePath = storage_path('app/official-documents');
            if (!is_dir($storagePath)) {
                $this->warnings[] = [
                    'service' => 'official_documents',
                    'severity' => 'MEDIUM',
                    'message' => 'Official documents storage directory does not exist',
                ];
            }

            // فحص عدد الوثائق
            $documentCount = DB::table('official_documents')->count();

            $this->results['official_documents_service'] = [
                'status' => 'OK',
                'message' => 'Official documents service is operational',
                'total_documents' => $documentCount,
            ];
        } catch (Exception $e) {
            $this->errors[] = [
                'service' => 'official_documents',
                'severity' => 'HIGH',
                'message' => 'Official documents service check failed: ' . $e->getMessage(),
            ];
            $this->results['official_documents_service'] = [
                'status' => 'ERROR',
                'message' => 'Service check failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * فحص خدمة الترجمة المعتمدة
     */
    protected function checkCertifiedTranslationService(): void
    {
        try {
            // فحص route الخدمة
            $response = Http::timeout(5)->get($this->baseUrl . '/services/certified-translation');

            if ($response->successful()) {
                $this->results['certified_translation_service'] = [
                    'status' => 'OK',
                    'message' => 'Certified translation page accessible',
                    'response_time' => $response->transferStats?->getTransferTime() ?? 0,
                ];
            } else {
                $this->errors[] = [
                    'service' => 'certified_translation',
                    'severity' => 'HIGH',
                    'message' => 'Certified translation page returned ' . $response->status(),
                ];
                $this->results['certified_translation_service'] = [
                    'status' => 'ERROR',
                    'message' => 'Page not accessible',
                    'status_code' => $response->status(),
                ];
            }
        } catch (Exception $e) {
            $this->errors[] = [
                'service' => 'certified_translation',
                'severity' => 'HIGH',
                'message' => 'Certified translation service check failed: ' . $e->getMessage(),
            ];
            $this->results['certified_translation_service'] = [
                'status' => 'ERROR',
                'message' => 'Service check failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * فحص لوحة الشركاء
     */
    protected function checkPartnerDashboard(): void
    {
        try {
            // فحص route الشركاء
            $response = Http::timeout(5)->get($this->baseUrl . '/partners');

            if ($response->successful()) {
                $this->results['partner_dashboard'] = [
                    'status' => 'OK',
                    'message' => 'Partner page accessible',
                ];
            } else {
                $this->warnings[] = [
                    'service' => 'partner_dashboard',
                    'severity' => 'MEDIUM',
                    'message' => 'Partner page returned ' . $response->status(),
                ];
                $this->results['partner_dashboard'] = [
                    'status' => 'WARNING',
                    'message' => 'Page not accessible',
                    'status_code' => $response->status(),
                ];
            }
        } catch (Exception $e) {
            $this->warnings[] = [
                'service' => 'partner_dashboard',
                'severity' => 'MEDIUM',
                'message' => 'Partner dashboard check failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * فحص نظام الأفلييت
     */
    protected function checkAffiliateSystem(): void
    {
        try {
            // فحص جدول الأفلييت
            if (DB::getSchemaBuilder()->hasTable('affiliate_links')) {
                $affiliateCount = DB::table('affiliate_links')->count();
                
                $this->results['affiliate_system'] = [
                    'status' => 'OK',
                    'message' => 'Affiliate system is operational',
                    'total_links' => $affiliateCount,
                ];
            } else {
                $this->warnings[] = [
                    'service' => 'affiliate_system',
                    'severity' => 'MEDIUM',
                    'message' => 'Affiliate links table does not exist',
                ];
                $this->results['affiliate_system'] = [
                    'status' => 'WARNING',
                    'message' => 'Database table missing',
                ];
            }
        } catch (Exception $e) {
            $this->warnings[] = [
                'service' => 'affiliate_system',
                'severity' => 'MEDIUM',
                'message' => 'Affiliate system check failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * فحص نظام الدفع
     */
    protected function checkPaymentSystem(): void
    {
        try {
            // فحص Stripe configuration
            $stripeKey = config('services.stripe.secret');
            
            if (empty($stripeKey)) {
                $this->warnings[] = [
                    'service' => 'payment_system',
                    'severity' => 'HIGH',
                    'message' => 'Stripe API key not configured',
                ];
                $this->results['payment_system'] = [
                    'status' => 'WARNING',
                    'message' => 'Stripe not configured',
                ];
                return;
            }

            // فحص جدول الاشتراكات
            if (DB::getSchemaBuilder()->hasTable('subscriptions')) {
                $subscriptionCount = DB::table('subscriptions')->where('stripe_status', 'active')->count();
                
                $this->results['payment_system'] = [
                    'status' => 'OK',
                    'message' => 'Payment system is operational',
                    'active_subscriptions' => $subscriptionCount,
                ];
            } else {
                $this->errors[] = [
                    'service' => 'payment_system',
                    'severity' => 'HIGH',
                    'message' => 'Subscriptions table does not exist',
                ];
                $this->results['payment_system'] = [
                    'status' => 'ERROR',
                    'message' => 'Database table missing',
                ];
            }
        } catch (Exception $e) {
            $this->errors[] = [
                'service' => 'payment_system',
                'severity' => 'HIGH',
                'message' => 'Payment system check failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * فحص نظام المصادقة
     */
    protected function checkAuthenticationSystem(): void
    {
        try {
            // فحص جدول المستخدمين
            if (!DB::getSchemaBuilder()->hasTable('users')) {
                $this->errors[] = [
                    'service' => 'authentication',
                    'severity' => 'CRITICAL',
                    'message' => 'Users table does not exist',
                ];
                $this->results['authentication_system'] = [
                    'status' => 'ERROR',
                    'message' => 'Database table missing',
                ];
                return;
            }

            // فحص عدد المستخدمين
            $userCount = DB::table('users')->count();

            // فحص صفحة تسجيل الدخول
            $response = Http::timeout(5)->get($this->baseUrl . '/login');

            if ($response->successful()) {
                $this->results['authentication_system'] = [
                    'status' => 'OK',
                    'message' => 'Authentication system is operational',
                    'total_users' => $userCount,
                    'login_page' => 'accessible',
                ];
            } else {
                $this->errors[] = [
                    'service' => 'authentication',
                    'severity' => 'CRITICAL',
                    'message' => 'Login page not accessible',
                ];
                $this->results['authentication_system'] = [
                    'status' => 'ERROR',
                    'message' => 'Login page not accessible',
                ];
            }
        } catch (Exception $e) {
            $this->errors[] = [
                'service' => 'authentication',
                'severity' => 'CRITICAL',
                'message' => 'Authentication system check failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * فحص الصفحات الرئيسية
     */
    protected function checkMainPages(): void
    {
        $pages = [
            'home' => '/',
            'features' => '/features',
            'pricing' => '/pricing',
            'about' => '/about',
            'contact' => '/contact',
            'careers' => '/careers',
            'help-center' => '/help-center',
        ];

        $failedPages = [];
        $successPages = 0;

        foreach ($pages as $name => $path) {
            try {
                $response = Http::timeout(5)->get($this->baseUrl . $path);
                
                if ($response->successful()) {
                    $successPages++;
                } else {
                    $failedPages[] = [
                        'page' => $name,
                        'path' => $path,
                        'status' => $response->status(),
                    ];
                }
            } catch (Exception $e) {
                $failedPages[] = [
                    'page' => $name,
                    'path' => $path,
                    'error' => $e->getMessage(),
                ];
            }
        }

        if (empty($failedPages)) {
            $this->results['main_pages'] = [
                'status' => 'OK',
                'message' => 'All main pages accessible',
                'total_pages' => count($pages),
            ];
        } else {
            $this->errors[] = [
                'service' => 'main_pages',
                'severity' => 'HIGH',
                'message' => count($failedPages) . ' pages are not accessible',
                'failed_pages' => $failedPages,
            ];
            $this->results['main_pages'] = [
                'status' => 'ERROR',
                'message' => 'Some pages not accessible',
                'success' => $successPages,
                'failed' => count($failedPages),
                'failed_pages' => $failedPages,
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
            'total_services' => $total,
            'operational' => $ok,
            'warnings' => $warning,
            'errors' => $error,
            'availability_percentage' => $total > 0 ? round(($ok / $total) * 100, 2) : 0,
        ];
    }
}
