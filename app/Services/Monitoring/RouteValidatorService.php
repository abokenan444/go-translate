<?php

namespace App\Services\Monitoring;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Exception;

class RouteValidatorService
{
    protected $results = [];
    protected $errors = [];
    protected $warnings = [];

    /**
     * فحص شامل لجميع Routes
     */
    public function validateAllRoutes(): array
    {
        $this->results = [];
        $this->errors = [];
        $this->warnings = [];

        // فحص تعريف Routes
        $this->checkRouteDefinitions();

        // فحص Routes المكررة
        $this->checkDuplicateRoutes();

        // فحص Controllers
        $this->checkControllers();

        // فحص Views المستخدمة في Routes
        $this->checkViews();

        // فحص Routes المستخدمة في Blade Templates
        $this->checkRoutesInViews();

        // فحص Route Cache
        $this->checkRouteCache();

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
     * فحص تعريف Routes
     */
    protected function checkRouteDefinitions(): void
    {
        try {
            $routes = Route::getRoutes();
            $totalRoutes = count($routes);

            $this->results['route_definitions'] = [
                'status' => 'OK',
                'message' => "Total routes defined: {$totalRoutes}",
                'total_routes' => $totalRoutes,
            ];
        } catch (Exception $e) {
            $this->errors[] = [
                'type' => 'route_definitions',
                'severity' => 'CRITICAL',
                'message' => 'Failed to load routes: ' . $e->getMessage(),
            ];
            $this->results['route_definitions'] = [
                'status' => 'ERROR',
                'message' => 'Cannot load routes',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * فحص Routes المكررة
     */
    protected function checkDuplicateRoutes(): void
    {
        try {
            $routes = Route::getRoutes();
            $namedRoutes = [];
            $duplicates = [];

            foreach ($routes as $route) {
                $name = $route->getName();
                if ($name) {
                    if (isset($namedRoutes[$name])) {
                        $duplicates[] = [
                            'name' => $name,
                            'uri1' => $namedRoutes[$name],
                            'uri2' => $route->uri(),
                        ];
                    } else {
                        $namedRoutes[$name] = $route->uri();
                    }
                }
            }

            if (empty($duplicates)) {
                $this->results['duplicate_routes'] = [
                    'status' => 'OK',
                    'message' => 'No duplicate route names found',
                    'named_routes' => count($namedRoutes),
                ];
            } else {
                $this->errors[] = [
                    'type' => 'duplicate_routes',
                    'severity' => 'HIGH',
                    'message' => 'Found ' . count($duplicates) . ' duplicate route names',
                    'duplicates' => $duplicates,
                ];
                $this->results['duplicate_routes'] = [
                    'status' => 'ERROR',
                    'message' => 'Duplicate route names detected',
                    'count' => count($duplicates),
                    'duplicates' => $duplicates,
                ];
            }
        } catch (Exception $e) {
            $this->errors[] = [
                'type' => 'duplicate_routes',
                'severity' => 'MEDIUM',
                'message' => 'Failed to check duplicates: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * فحص Controllers
     */
    protected function checkControllers(): void
    {
        try {
            $routes = Route::getRoutes();
            $missingControllers = [];

            foreach ($routes as $route) {
                $action = $route->getAction();
                
                if (isset($action['controller'])) {
                    $controllerClass = explode('@', $action['controller'])[0];
                    
                    if (!class_exists($controllerClass)) {
                        $missingControllers[] = [
                            'route' => $route->getName() ?? $route->uri(),
                            'controller' => $controllerClass,
                        ];
                    }
                }
            }

            if (empty($missingControllers)) {
                $this->results['controllers'] = [
                    'status' => 'OK',
                    'message' => 'All controllers exist',
                ];
            } else {
                $this->errors[] = [
                    'type' => 'controllers',
                    'severity' => 'HIGH',
                    'message' => 'Found ' . count($missingControllers) . ' missing controllers',
                    'missing' => $missingControllers,
                ];
                $this->results['controllers'] = [
                    'status' => 'ERROR',
                    'message' => 'Missing controllers detected',
                    'count' => count($missingControllers),
                    'missing' => $missingControllers,
                ];
            }
        } catch (Exception $e) {
            $this->warnings[] = [
                'type' => 'controllers',
                'severity' => 'MEDIUM',
                'message' => 'Failed to check controllers: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * فحص Views
     */
    protected function checkViews(): void
    {
        try {
            $viewsPath = resource_path('views');
            $viewFiles = File::allFiles($viewsPath);
            
            $this->results['views'] = [
                'status' => 'OK',
                'message' => 'Views directory accessible',
                'total_views' => count($viewFiles),
            ];
        } catch (Exception $e) {
            $this->errors[] = [
                'type' => 'views',
                'severity' => 'HIGH',
                'message' => 'Failed to access views: ' . $e->getMessage(),
            ];
            $this->results['views'] = [
                'status' => 'ERROR',
                'message' => 'Cannot access views directory',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * فحص Routes المستخدمة في Blade Templates
     */
    protected function checkRoutesInViews(): void
    {
        try {
            $viewsPath = resource_path('views');
            $viewFiles = File::allFiles($viewsPath);
            
            $usedRoutes = [];
            $missingRoutes = [];
            
            foreach ($viewFiles as $file) {
                if ($file->getExtension() === 'php') {
                    $content = File::get($file->getPathname());
                    
                    // البحث عن route('route.name')
                    preg_match_all("/route\(['\"]([^'\"]+)['\"]\)/", $content, $matches);
                    
                    if (!empty($matches[1])) {
                        foreach ($matches[1] as $routeName) {
                            $usedRoutes[] = $routeName;
                            
                            if (!Route::has($routeName)) {
                                $missingRoutes[] = [
                                    'route' => $routeName,
                                    'file' => str_replace($viewsPath . '/', '', $file->getPathname()),
                                ];
                            }
                        }
                    }
                }
            }

            $uniqueUsedRoutes = array_unique($usedRoutes);

            if (empty($missingRoutes)) {
                $this->results['routes_in_views'] = [
                    'status' => 'OK',
                    'message' => 'All routes used in views are defined',
                    'total_routes_used' => count($uniqueUsedRoutes),
                ];
            } else {
                $this->errors[] = [
                    'type' => 'routes_in_views',
                    'severity' => 'CRITICAL',
                    'message' => 'Found ' . count($missingRoutes) . ' undefined routes in views',
                    'missing' => $missingRoutes,
                ];
                $this->results['routes_in_views'] = [
                    'status' => 'ERROR',
                    'message' => 'Undefined routes found in views',
                    'count' => count($missingRoutes),
                    'missing' => array_slice($missingRoutes, 0, 10), // أول 10 فقط
                    'total_missing' => count($missingRoutes),
                ];
            }
        } catch (Exception $e) {
            $this->warnings[] = [
                'type' => 'routes_in_views',
                'severity' => 'MEDIUM',
                'message' => 'Failed to check routes in views: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * فحص Route Cache
     */
    protected function checkRouteCache(): void
    {
        try {
            $cacheFile = base_path('bootstrap/cache/routes-v7.php');
            
            if (File::exists($cacheFile)) {
                $age = time() - File::lastModified($cacheFile);
                $ageInHours = round($age / 3600, 1);
                
                if ($ageInHours > 24) {
                    $this->warnings[] = [
                        'type' => 'route_cache',
                        'severity' => 'LOW',
                        'message' => "Route cache is {$ageInHours} hours old. Consider refreshing.",
                    ];
                    $this->results['route_cache'] = [
                        'status' => 'WARNING',
                        'message' => 'Route cache is old',
                        'age_hours' => $ageInHours,
                        'recommendation' => 'Run: php artisan route:cache',
                    ];
                } else {
                    $this->results['route_cache'] = [
                        'status' => 'OK',
                        'message' => 'Route cache is fresh',
                        'age_hours' => $ageInHours,
                    ];
                }
            } else {
                $this->warnings[] = [
                    'type' => 'route_cache',
                    'severity' => 'LOW',
                    'message' => 'Route cache file does not exist',
                ];
                $this->results['route_cache'] = [
                    'status' => 'WARNING',
                    'message' => 'No route cache found',
                    'recommendation' => 'Run: php artisan route:cache for better performance',
                ];
            }
        } catch (Exception $e) {
            $this->warnings[] = [
                'type' => 'route_cache',
                'severity' => 'LOW',
                'message' => 'Failed to check route cache: ' . $e->getMessage(),
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
            'critical_issues' => count(array_filter($this->errors, fn($e) => $e['severity'] === 'CRITICAL')),
        ];
    }

    /**
     * الحصول على قائمة بجميع Routes المعرفة
     */
    public function getAllDefinedRoutes(): array
    {
        $routes = Route::getRoutes();
        $result = [];

        foreach ($routes as $route) {
            $result[] = [
                'name' => $route->getName(),
                'uri' => $route->uri(),
                'methods' => $route->methods(),
                'action' => $route->getActionName(),
            ];
        }

        return $result;
    }
}
