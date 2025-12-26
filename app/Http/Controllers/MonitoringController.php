<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Monitoring\HealthCheckService;
use App\Services\Monitoring\RouteValidatorService;
use App\Services\Monitoring\ServiceIntegrityChecker;
use Illuminate\Support\Facades\Storage;

class MonitoringController extends Controller
{
    protected $healthCheckService;
    protected $routeValidatorService;
    protected $serviceIntegrityChecker;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'super_admin') {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });

        $this->healthCheckService = new HealthCheckService();
        $this->routeValidatorService = new RouteValidatorService();
        $this->serviceIntegrityChecker = new ServiceIntegrityChecker();
    }

    /**
     * عرض لوحة المراقبة
     */
    public function dashboard()
    {
        return view('monitoring.dashboard');
    }

    /**
     * الحصول على حالة النظام (API)
     */
    public function getStatus(Request $request)
    {
        $type = $request->get('type', 'quick');

        $data = [];

        if ($type === 'full' || $type === 'health') {
            $data['health'] = $this->healthCheckService->runFullCheck();
        }

        if ($type === 'full' || $type === 'routes') {
            $data['routes'] = $this->routeValidatorService->validateAllRoutes();
        }

        if ($type === 'full' || $type === 'services') {
            $data['services'] = $this->serviceIntegrityChecker->checkAllServices();
        }

        if ($type === 'quick') {
            $data['health'] = $this->healthCheckService->runFullCheck();
        }

        // تحديد الحالة العامة
        $overallStatus = 'OK';
        foreach ($data as $check) {
            if (isset($check['status']) && in_array($check['status'], ['ERROR', 'CRITICAL'])) {
                $overallStatus = 'ERROR';
                break;
            } elseif (isset($check['status']) && $check['status'] === 'WARNING' && $overallStatus === 'OK') {
                $overallStatus = 'WARNING';
            }
        }

        return response()->json([
            'overall_status' => $overallStatus,
            'timestamp' => now()->toDateTimeString(),
            'data' => $data,
        ]);
    }

    /**
     * الحصول على التقارير المحفوظة
     */
    public function getReports()
    {
        $files = Storage::files('monitoring/reports');
        $reports = [];

        foreach ($files as $file) {
            $reports[] = [
                'filename' => basename($file),
                'path' => $file,
                'size' => Storage::size($file),
                'modified' => Storage::lastModified($file),
                'url' => route('monitoring.download-report', ['file' => basename($file)]),
            ];
        }

        // ترتيب حسب التاريخ (الأحدث أولاً)
        usort($reports, function ($a, $b) {
            return $b['modified'] - $a['modified'];
        });

        return response()->json($reports);
    }

    /**
     * تحميل تقرير محدد
     */
    public function downloadReport($file)
    {
        $path = 'monitoring/reports/' . $file;

        if (!Storage::exists($path)) {
            abort(404);
        }

        return Storage::download($path);
    }

    /**
     * الحصول على التنبيهات
     */
    public function getAlerts()
    {
        $files = Storage::files('monitoring/alerts');
        $alerts = [];

        foreach ($files as $file) {
            $content = json_decode(Storage::get($file), true);
            $alerts[] = [
                'filename' => basename($file),
                'timestamp' => $content['timestamp'] ?? null,
                'status' => $content['status'] ?? 'UNKNOWN',
                'errors_count' => count($content['errors'] ?? []),
                'errors' => $content['errors'] ?? [],
            ];
        }

        // ترتيب حسب التاريخ (الأحدث أولاً)
        usort($alerts, function ($a, $b) {
            return strcmp($b['timestamp'] ?? '', $a['timestamp'] ?? '');
        });

        return response()->json($alerts);
    }

    /**
     * تشغيل فحص يدوي
     */
    public function runCheck(Request $request)
    {
        $type = $request->get('type', 'quick');

        $results = [];

        if ($type === 'full') {
            $results['health'] = $this->healthCheckService->runFullCheck();
            $results['routes'] = $this->routeValidatorService->validateAllRoutes();
            $results['services'] = $this->serviceIntegrityChecker->checkAllServices();
        } else {
            $results['health'] = $this->healthCheckService->runFullCheck();
        }

        // حفظ التقرير
        $filename = 'health-check-manual-' . now()->format('Y-m-d_His') . '.json';
        Storage::put('monitoring/reports/' . $filename, json_encode($results, JSON_PRETTY_PRINT));

        return response()->json([
            'success' => true,
            'message' => 'Check completed successfully',
            'results' => $results,
            'report_file' => $filename,
        ]);
    }
}
