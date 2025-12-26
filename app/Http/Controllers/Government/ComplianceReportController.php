<?php

namespace App\Http\Controllers\Government;

use App\Http\Controllers\Controller;
use App\Models\OfficialDocument;
use App\Models\Certificate;
use App\Models\Dispute;
use App\Models\DecisionLedgerEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class ComplianceReportController extends Controller
{
    /**
     * Generate compliance report (HTML, PDF, or CSV)
     */
    public function generate(Request $request)
    {
        $user = auth()->user();
        
        // Get entity scope
        $entityId = $user->governmentProfile?->gov_entity_id;
        if (!$entityId) {
            abort(403, 'No government entity assigned');
        }
        
        // Validate and parse dates
        $validated = $request->validate([
            'start_date' => 'nullable|date|before_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date|before_or_equal:today',
            'format' => 'nullable|in:html,pdf,csv'
        ]);
        
        // Date range (default: last 30 days)
        $startDate = $request->input('start_date') 
            ? \Carbon\Carbon::parse($request->input('start_date'))->startOfDay() 
            : now()->subDays(30)->startOfDay();
        $endDate = $request->input('end_date') 
            ? \Carbon\Carbon::parse($request->input('end_date'))->endOfDay() 
            : now()->endOfDay();
        $format = $request->input('format', 'html'); // html, pdf, csv
        
        // Build report data
        $data = $this->buildReportData($entityId, $startDate, $endDate);
        
        // Return based on format
        switch ($format) {
            case 'pdf':
                return $this->generatePdf($data);
            
            case 'csv':
                return $this->generateCsv($data);
            
            default:
                return view('government.compliance-report', $data);
        }
    }
    
    /**
     * Build compliance report data
     */
    protected function buildReportData(int $entityId, $startDate, $endDate): array
    {
        // Total documents submitted
        $totalDocuments = OfficialDocument::where('gov_entity_id', $entityId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        
        // Documents by status
        $documentsByStatus = OfficialDocument::where('gov_entity_id', $entityId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
        
        // Certificates issued
        $certificatesIssued = Certificate::whereHas('officialDocument', function($q) use ($entityId, $startDate, $endDate) {
            $q->where('gov_entity_id', $entityId)
              ->whereBetween('created_at', [$startDate, $endDate]);
        })->count();
        
        // Compliance rate (certificates / completed documents)
        $completedDocuments = $documentsByStatus['completed'] ?? 0;
        $complianceRate = $completedDocuments > 0 
            ? round(($certificatesIssued / $completedDocuments) * 100, 2) 
            : 0;
        
        // Average processing time (hours)
        $avgProcessingTime = OfficialDocument::where('gov_entity_id', $entityId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('completed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, completed_at)) as avg_hours')
            ->value('avg_hours');
        
        // Disputes
        $totalDisputes = Dispute::whereHas('certificate.officialDocument', function($q) use ($entityId) {
            $q->where('gov_entity_id', $entityId);
        })->whereBetween('created_at', [$startDate, $endDate])
          ->count();
        
        $disputesByStatus = Dispute::whereHas('certificate.officialDocument', function($q) use ($entityId) {
            $q->where('gov_entity_id', $entityId);
        })->whereBetween('created_at', [$startDate, $endDate])
          ->select('status', DB::raw('count(*) as count'))
          ->groupBy('status')
          ->pluck('count', 'status')
          ->toArray();
        
        // Dispute rate
        $disputeRate = $certificatesIssued > 0 
            ? round(($totalDisputes / $certificatesIssued) * 100, 2) 
            : 0;
        
        // Ledger events
        $ledgerEvents = DecisionLedgerEvent::where('entity_id', $entityId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('event_type', DB::raw('count(*) as count'))
            ->groupBy('event_type')
            ->pluck('count', 'event_type')
            ->toArray();
        
        // Documents by type
        $documentsByType = OfficialDocument::where('gov_entity_id', $entityId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('document_type', DB::raw('count(*) as count'))
            ->groupBy('document_type')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        // Recent certificates with details
        $recentCertificates = Certificate::with(['officialDocument', 'revocation'])
            ->whereHas('officialDocument', function($q) use ($entityId, $startDate, $endDate) {
                $q->where('gov_entity_id', $entityId)
                  ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->orderByDesc('issued_at')
            ->limit(50)
            ->get();
        
        return [
            'entity_id' => $entityId,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'report_generated_at' => now(),
            
            // Summary metrics
            'total_documents' => $totalDocuments,
            'certificates_issued' => $certificatesIssued,
            'compliance_rate' => $complianceRate,
            'avg_processing_hours' => round($avgProcessingTime ?? 0, 1),
            'total_disputes' => $totalDisputes,
            'dispute_rate' => $disputeRate,
            
            // Breakdowns
            'documents_by_status' => $documentsByStatus,
            'documents_by_type' => $documentsByType,
            'disputes_by_status' => $disputesByStatus,
            'ledger_events' => $ledgerEvents,
            
            // Details
            'recent_certificates' => $recentCertificates,
        ];
    }
    
    /**
     * Generate PDF version of report
     */
    protected function generatePdf(array $data)
    {
        $filename = sprintf(
            'compliance_report_%s_to_%s.pdf',
            $data['start_date']->format('Y-m-d'),
            $data['end_date']->format('Y-m-d')
        );
        
        try {
            // Render HTML view
            $html = view('government.compliance-report-pdf', $data)->render();
            
            // Create mPDF instance
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L', // Landscape
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
                'default_font' => 'dejavusans'
            ]);
            
            // Set metadata
            $mpdf->SetTitle('Compliance Report');
            $mpdf->SetAuthor('Cultural Translate Platform');
            
            // Write HTML to PDF
            $mpdf->WriteHTML($html);
            
            // Output as download
            return response()->streamDownload(function() use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, $filename, [
                'Content-Type' => 'application/pdf'
            ]);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate CSV version of report
     */
    protected function generateCsv(array $data)
    {
        $filename = sprintf(
            'compliance_report_%s_to_%s.csv',
            $data['start_date']->format('Y-m-d'),
            $data['end_date']->format('Y-m-d')
        );
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Summary section
            fputcsv($file, ['COMPLIANCE REPORT SUMMARY']);
            fputcsv($file, ['Report Period', $data['start_date']->format('Y-m-d') . ' to ' . $data['end_date']->format('Y-m-d')]);
            fputcsv($file, ['Generated At', $data['report_generated_at']->format('Y-m-d H:i:s')]);
            fputcsv($file, []);
            
            // Key metrics
            fputcsv($file, ['METRICS']);
            fputcsv($file, ['Total Documents', $data['total_documents']]);
            fputcsv($file, ['Certificates Issued', $data['certificates_issued']]);
            fputcsv($file, ['Compliance Rate (%)', $data['compliance_rate']]);
            fputcsv($file, ['Avg Processing Time (hours)', $data['avg_processing_hours']]);
            fputcsv($file, ['Total Disputes', $data['total_disputes']]);
            fputcsv($file, ['Dispute Rate (%)', $data['dispute_rate']]);
            fputcsv($file, []);
            
            // Documents by status
            fputcsv($file, ['DOCUMENTS BY STATUS']);
            fputcsv($file, ['Status', 'Count']);
            foreach ($data['documents_by_status'] as $status => $count) {
                fputcsv($file, [$status, $count]);
            }
            fputcsv($file, []);
            
            // Documents by type
            fputcsv($file, ['DOCUMENTS BY TYPE']);
            fputcsv($file, ['Document Type', 'Count']);
            foreach ($data['documents_by_type'] as $doc) {
                fputcsv($file, [$doc->document_type, $doc->count]);
            }
            fputcsv($file, []);
            
            // Recent certificates
            fputcsv($file, ['RECENT CERTIFICATES']);
            fputcsv($file, ['Certificate ID', 'Document ID', 'Status', 'Issued At', 'Legal Status']);
            foreach ($data['recent_certificates'] as $cert) {
                fputcsv($file, [
                    $cert->id,
                    $cert->document_id,
                    $cert->status,
                    $cert->issued_at?->format('Y-m-d H:i:s'),
                    $cert->legal_status ?? 'valid'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
