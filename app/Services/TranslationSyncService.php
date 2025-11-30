<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class TranslationSyncService
{
    /**
     * Generate/update page translations for a locale using external API.
     * Returns array with 'success' and 'files'.
     */
    public function syncPages(string $locale): array
    {
        $sourcePath = base_path('lang/en/pages.php');
        $targetDir = base_path('lang/' . $locale);
        $targetPath = $targetDir . '/pages.php';

        if (!File::exists($sourcePath)) {
            return ['success' => false, 'error' => 'Source pages.php (EN) missing'];
        }

        if (!File::exists($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $source = include $sourcePath; // array structure

        // Call external API to translate keys (replace with your endpoint)
        // Example: POST /api/translate-pages with { locale, content }
        try {
            $endpoint = config('services.translation_api.pages_endpoint');
            $response = Http::timeout(20)->post($endpoint, [
                'locale' => $locale,
                'content' => $source,
            ]);

            if (!$response->ok()) {
                // Fallback: copy English
                File::put($targetPath, "<?php\n\nreturn " . var_export($source, true) . ";\n");
                return ['success' => false, 'files' => [$targetPath], 'error' => 'API not OK, copied EN'];
            }

            $translated = $response->json('data.translated');
            if (!is_array($translated)) {
                File::put($targetPath, "<?php\n\nreturn " . var_export($source, true) . ";\n");
                return ['success' => false, 'files' => [$targetPath], 'error' => 'Invalid API data, copied EN'];
            }

            File::put($targetPath, "<?php\n\nreturn " . var_export($translated, true) . ";\n");
            return ['success' => true, 'files' => [$targetPath]];
        } catch (\Throwable $e) {
            File::put($targetPath, "<?php\n\nreturn " . var_export($source, true) . ";\n");
            return ['success' => false, 'files' => [$targetPath], 'error' => $e->getMessage()];
        }
    }
}
