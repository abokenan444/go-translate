<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Professional Seal...\n";
DB::table('official_documents')->truncate();
DB::table('document_translations')->truncate();
DB::table('document_certificates')->truncate();

$document = \App\Models\OfficialDocument::create([
    'user_id' => 1,
    'document_type' => 'passport',
    'source_language' => 'ar',
    'target_language' => 'en',
    'original_file_path' => 'documents/test.pdf',
    'original_hash' => md5('test'),
    'status' => 'pending',
]);

echo "Document: {$document->id}\n";

$certificate = \App\Models\DocumentCertificate::create([
    'document_id' => $document->id,
    'cert_id' => 'CT-PROF-' . strtoupper(substr(md5(uniqid()), 0, 8)),
    'original_hash' => md5('original'),
    'translated_hash' => md5('translated'),
    'issued_at' => now(),
    'expires_at' => now()->addYears(5),
    'status' => 'valid',
]);

echo "Certificate: {$certificate->cert_id}\n";

$translation = \App\Models\DocumentTranslation::create([
    'official_document_id' => $document->id,
    'translated_file_path' => 'documents/translations/' . $document->id . '-translated.pdf',
]);

$dummyPdfPath = storage_path('app/documents/translations/' . $document->id . '-translated.pdf');
@mkdir(dirname($dummyPdfPath), 0755, true);

$pdf = new \setasign\Fpdi\Fpdi();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Test Document - Professional Seal', 0, 1, 'C');
$pdf->Output('F', $dummyPdfPath);

echo "Dummy PDF created\n";

$pdfService = app(\App\Services\PdfCertificationService::class);
$certifiedPath = $pdfService->applySealAndStatement(
    $translation->translated_file_path,
    $document,
    $certificate
);

echo "Certification applied\n";
echo "File: {$certifiedPath}\n";
echo "Certificate ID: {$certificate->cert_id}\n";
echo "Document ID: {$document->id}\n";
