<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AiDevChange;
use App\Services\AIDeveloperService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class AIDeveloperController extends Controller
{
    public function __construct(
        protected AIDeveloperService $service
    ) {
    }

    public function index(Request $request)
    {
        $changes = AiDevChange::orderByDesc('created_at')->limit(50)->get();

        return view('ai-developer.index', [
            'changes' => $changes,
        ]);
    }

    public function chat(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'min:3'],
        ]);

        $user = $request->user();

        $result = $this->service->handlePrompt($request->string('message'), $user);

        return redirect()
            ->route('ai-developer.index')
            ->with('ai_analysis', $result['analysis'] ?? '')
            ->with('ai_raw', $result['raw'] ?? null);
    }

    public function applyChange(Request $request, AiDevChange $change): RedirectResponse
    {
        $this->ensureOwner($request);

        if ($change->status !== 'pending') {
            return back()->with('error', 'This change is not pending.');
        }

        if ($change->type === 'file_edit') {
            $fullPath = base_path($change->target_path);

            if (file_exists($fullPath)) {
                file_put_contents($fullPath . '.bak_' . now()->format('Ymd_His'), file_get_contents($fullPath));
            }

            file_put_contents($fullPath, $change->proposed_content);

            $change->status = 'applied';
            $change->save();

            return back()->with('success', 'File updated successfully.');
        }

        if ($change->type === 'command') {
            $cmd = trim($change->command);

            $allowed = config('ai_developer.allowed_commands', []);
            if (! in_array($cmd, $allowed, true)) {
                return back()->with('error', 'Command not allowed for security reasons.');
            }

            $process = Process::fromShellCommandline($cmd, base_path());
            $process->setTimeout(300);
            $process->run();

            $meta = $change->meta ?? [];
            $meta['output']   = $process->getOutput();
            $meta['error']    = $process->getErrorOutput();
            $meta['exitCode'] = $process->getExitCode();

            $change->meta   = $meta;
            $change->status = 'applied';
            $change->save();

            return back()
                ->with('success', 'Command executed.')
                ->with('command_output', $process->getOutput())
                ->with('command_error', $process->getErrorOutput());
        }

        if ($change->type === 'sql') {
            DB::statement(DB::raw($change->sql));

            $change->status = 'applied';
            $change->save();

            return back()->with('success', 'SQL executed successfully.');
        }

        return back()->with('error', 'Unknown change type.');
    }

    public function cancelChange(Request $request, AiDevChange $change): RedirectResponse
    {
        $this->ensureOwner($request);

        if ($change->status !== 'pending') {
            return back()->with('error', 'This change is not pending.');
        }

        $change->status = 'cancelled';
        $change->save();

        return back()->with('success', 'Change cancelled.');
    }

    public function publicHealth()
    {
        try {
            DB::connection()->getPdo();
            $dbOk = true;
        } catch (\Throwable $e) {
            $dbOk = false;
        }

        $statusCode = $dbOk ? 200 : 500;

        return response()->json([
            'status' => $dbOk ? 'ok' : 'error',
        ], $statusCode);
    }

    public function apiHealth()
    {
        try {
            DB::connection()->getPdo();
            $dbOk = true;
        } catch (\Throwable $e) {
            $dbOk = false;
        }

        return response()->json([
            'app' => [
                'env'     => config('app.env'),
                'debug'   => config('app.debug'),
                'version' => app()->version(),
            ],
            'db' => [
                'status' => $dbOk ? 'ok' : 'error',
            ],
            'time' => now()->toIso8601String(),
        ]);
    }

    public function deploy(Request $request): RedirectResponse
    {
        $this->ensureOwner($request);

        $request->validate([
            'action' => ['required', 'string'],
        ]);

        $action = $request->string('action');

        $map = [
            'clear_cache'   => 'php artisan optimize:clear',
            'migrate'       => 'php artisan migrate --force',
            'queue_restart' => 'php artisan queue:restart',
            'config_cache'  => 'php artisan config:cache',
        ];

        if (! isset($map[(string)$action])) {
            return back()->with('error', 'Unknown deploy action.');
        }

        $cmd = $map[(string)$action];

        $allowed = config('ai_developer.allowed_commands', []);
        if (! in_array($cmd, $allowed, true)) {
            return back()->with('error', 'This action command is not allowed in config/ai_developer.php');
        }

        $process = Process::fromShellCommandline($cmd, base_path());
        $process->setTimeout(300);
        $process->run();

        return back()
            ->with('success', 'Deploy action executed: ' . $action)
            ->with('command_output', $process->getOutput())
            ->with('command_error', $process->getErrorOutput());
    }

    protected function ensureOwner(Request $request): void
    {
        // Check session instead of user
    }

    /**
     * Show login page
     */
    public function showLogin()
    {
        // If already authenticated, redirect to dashboard
        if (session()->has('ai_developer_authenticated')) {
            return redirect()->route('ai-developer.index');
        }
        
        return view('ai-developer.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $correctPassword = env('AI_DEV_ACCESS_PASSWORD');
        
        if (!$correctPassword) {
            return back()->with('error', 'AI Developer password not configured in .env');
        }

        if ($request->password === $correctPassword) {
            session()->put('ai_developer_authenticated', true);
            session()->put('ai_developer_login_time', now());
            
            return redirect()->route('ai-developer.index')->with('success', 'Welcome to AI Developer System!');
        }

        return back()->with('error', 'Invalid password');
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        session()->forget('ai_developer_authenticated');
        session()->forget('ai_developer_login_time');
        
        return redirect()->route('ai-developer.login')->with('success', 'Logged out successfully');
    }
}
