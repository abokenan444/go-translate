<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    echo "Testing Audit System Components\n";
    echo "===============================\n\n";
    
    // 1. Check audit_runs table
    echo "1. Checking audit_runs table...\n";
    if (Schema::hasTable('audit_runs')) {
        echo "✅ audit_runs table exists\n";
        
        $columns = ['id', 'scope', 'dir', 'status', 'total', 'passed', 'failed', 
                    'is_release_candidate', 'marked_by', 'marked_at', 'release_notes'];
        foreach ($columns as $col) {
            $has = Schema::hasColumn('audit_runs', $col);
            echo "   " . ($has ? "✅" : "❌") . " Column: $col\n";
        }
        
        $count = DB::table('audit_runs')->count();
        echo "   Records: $count\n";
    } else {
        echo "❌ audit_runs table not found\n";
    }
    
    // 2. Check AuditRun model
    echo "\n2. Checking AuditRun model...\n";
    if (class_exists('App\Models\AuditRun')) {
        echo "✅ AuditRun model exists\n";
    } else {
        echo "❌ AuditRun model not found\n";
    }
    
    // 3. Check AuditInternalController
    echo "\n3. Checking AuditInternalController...\n";
    if (class_exists('App\Http\Controllers\Internal\AuditInternalController')) {
        echo "✅ AuditInternalController exists\n";
    } else {
        echo "❌ AuditInternalController not found\n";
    }
    
    // 4. Check EnsureInternalAdmin middleware
    echo "\n4. Checking EnsureInternalAdmin middleware...\n";
    if (class_exists('App\Http\Middleware\EnsureInternalAdmin')) {
        echo "✅ EnsureInternalAdmin middleware exists\n";
    } else {
        echo "❌ EnsureInternalAdmin middleware not found\n";
    }
    
    // 5. Check CtAuditPage
    echo "\n5. Checking CtAuditPage...\n";
    if (class_exists('App\Filament\Admin\Pages\CtAuditPage')) {
        echo "✅ CtAuditPage exists\n";
    } else {
        echo "❌ CtAuditPage not found\n";
    }
    
    // 6. Check routes
    echo "\n6. Checking routes...\n";
    $routes = collect(app('router')->getRoutes())->map(fn($r) => $r->getName())->filter();
    
    $expectedRoutes = [
        'internal.audit.index',
        'internal.audit.run',
        'internal.audit.download',
        'internal.audit.view',
    ];
    
    foreach ($expectedRoutes as $route) {
        $exists = $routes->contains($route);
        echo "   " . ($exists ? "✅" : "❌") . " Route: $route\n";
    }
    
    echo "\n===============================\n";
    echo "All audit system components checked!\n";
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
