<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\OfficialDocument;
use App\Jobs\ProcessOfficialDocumentTranslation;

$failedDocs = OfficialDocument::where('status', 'failed')->get();

echo "Found " . $failedDocs->count() . " failed documents\n";

foreach ($failedDocs as $doc) {
    echo "Retrying document ID: {$doc->id}\n";
    try {
        ProcessOfficialDocumentTranslation::dispatchSync($doc);
    } catch (\Exception $e) {
        echo "  ❌ Failed: " . $e->getMessage() . "\n";
    }
}

