<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class AuditInternalController extends Controller
{
    public function index()
    {
        $base = 'audits';
        $dirs = Storage::disk('local')->directories($base);

        // Sort newest first
        rsort($dirs);

        $items = collect($dirs)->map(function ($dir) {
            return [
                'dir' => $dir,
                'json' => Storage::disk('local')->exists("$dir/report.json"),
                'html' => Storage::disk('local')->exists("$dir/report.html"),
                'created_at' => $this->guessCreatedAtFromDir($dir),
            ];
        })->values();

        return response()->json([
            'success' => true,
            'audits' => $items,
        ]);
    }

    public function run(Request $request)
    {
        $modules = $request->input('modules', ['gov','authority','evidence','security','pdf']);
        $modules = array_values(array_unique(array_filter($modules)));

        $format = $request->input('report', 'html');
        $format = in_array($format, ['html','json'], true) ? $format : 'html';

        $output = $request->input('output') ?: now()->format('Ymd_His');

        $args = ['--report' => $format, '--output' => $output];

        // Validate modules
        $allowed = ['gov','authority','evidence','security','pdf'];
        foreach ($modules as $m) {
            if (!in_array($m, $allowed, true)) {
                return response()->json(['success'=>false,'message'=>"Invalid module: $m"], 422);
            }
            $args["--{$m}"] = true;
        }

        Artisan::call('ct:audit', $args);

        $dir = "audits/$output";

        return response()->json([
            'success' => true,
            'message' => 'Audit executed',
            'dir' => $dir,
            'paths' => [
                'json' => Storage::disk('local')->exists("$dir/report.json") ? "$dir/report.json" : null,
                'html' => Storage::disk('local')->exists("$dir/report.html") ? "$dir/report.html" : null,
            ],
        ]);
    }

    public function download(Request $request)
    {
        $path = (string) $request->query('path', '');
        if (!$path || !str_starts_with($path, 'audits/')) {
            abort(404);
        }

        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        $filename = basename($path);

        return Storage::disk('local')->download($path, $filename);
    }

    public function viewHtml(Request $request)
    {
        $dir = (string) $request->query('dir', '');
        $path = $dir ? trim($dir, '/') . '/report.html' : '';

        if (!$path || !str_starts_with($path, 'audits/')) {
            abort(404);
        }

        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return response(Storage::disk('local')->get($path))
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }

    private function guessCreatedAtFromDir(string $dir): ?string
    {
        // audits/20251219_120301
        $name = basename($dir);
        return $name;
    }
}
