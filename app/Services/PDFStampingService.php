<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\StreamReader;

/**
 * PDF Stamping Service
 * 
 * خدمة إضافة الأختام الديناميكية على ملفات PDF
 * تدعم:
 * - Platform Stamp (ختم المنصة)
 * - Partner Stamp (ختم الشريك)
 * - Government Stamp (ختم حكومي)
 * - Custom SVG Stamps
 * - Multiple stamp positions
 * - Watermarks
 */
class PDFStampingService
{
    /**
     * Apply stamp to PDF document
     * 
     * @param string $pdfPath Path to PDF file
     * @param array $stampConfig Stamp configuration
     * @return array
     */
    public function applyStamp(string $pdfPath, array $stampConfig): array
    {
        try {
            // Validate PDF exists
            if (!Storage::exists($pdfPath)) {
                throw new \Exception("PDF file not found: {$pdfPath}");
            }

            // Get full path
            $fullPath = Storage::path($pdfPath);

            // Create FPDI instance
            $pdf = new Fpdi();
            
            // Get page count
            $pageCount = $pdf->setSourceFile($fullPath);

            // Process each page
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // Import page
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);
                
                // Add page with same orientation
                $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
                $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                
                // Use imported page as template
                $pdf->useTemplate($templateId);

                // Apply stamp based on configuration
                if ($this->shouldApplyStampOnPage($pageNo, $pageCount, $stampConfig)) {
                    $this->addStampToPage($pdf, $size, $stampConfig);
                }

                // Apply watermark if configured
                if (isset($stampConfig['watermark']) && $stampConfig['watermark']) {
                    $this->addWatermark($pdf, $size, $stampConfig);
                }
            }

            // Generate stamped filename
            $stamped_filename = $this->generateStampedFilename($pdfPath);
            $stamped_path = dirname($pdfPath) . '/' . $stamped_filename;
            
            // Save stamped PDF
            $output = $pdf->Output('S'); // Output as string
            Storage::put($stamped_path, $output);

            Log::info('PDF stamped successfully', [
                'original' => $pdfPath,
                'stamped' => $stamped_path
            ]);

            return [
                'success' => true,
                'original_path' => $pdfPath,
                'stamped_path' => $stamped_path,
                'stamp_type' => $stampConfig['type'] ?? 'default',
                'pages_processed' => $pageCount
            ];

        } catch (\Exception $e) {
            Log::error('PDF stamping failed', [
                'error' => $e->getMessage(),
                'pdf_path' => $pdfPath
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Add stamp to current page
     * 
     * @param Fpdi $pdf
     * @param array $size
     * @param array $config
     */
    protected function addStampToPage(Fpdi $pdf, array $size, array $config): void
    {
        $type = $config['type'] ?? 'platform';
        $position = $config['position'] ?? 'bottom_right';
        $stampSize = $config['size'] ?? 'medium';

        // Get stamp dimensions
        $dimensions = $this->getStampDimensions($stampSize);
        
        // Get stamp position coordinates
        $coordinates = $this->calculateStampPosition($position, $size, $dimensions);

        // Generate or load stamp image
        $stampImagePath = $this->getStampImage($type, $config);

        if ($stampImagePath && file_exists($stampImagePath)) {
            // Add stamp image to PDF
            $pdf->Image(
                $stampImagePath,
                $coordinates['x'],
                $coordinates['y'],
                $dimensions['width'],
                $dimensions['height'],
                '',
                '',
                'T', // Transparent
                false,
                300,
                '',
                false,
                false,
                0
            );
        } else {
            // Fallback: Draw stamp with text
            $this->drawTextStamp($pdf, $coordinates, $dimensions, $config);
        }

        // Add stamp text if configured
        if (isset($config['text']) && $config['text']) {
            $this->addStampText($pdf, $coordinates, $dimensions, $config['text']);
        }

        // Add QR code if configured
        if (isset($config['qr_code']) && $config['qr_code']) {
            $this->addQRCodeToStamp($pdf, $coordinates, $dimensions, $config['qr_code']);
        }
    }

    /**
     * Add watermark to page
     * 
     * @param Fpdi $pdf
     * @param array $size
     * @param array $config
     */
    protected function addWatermark(Fpdi $pdf, array $size, array $config): void
    {
        $watermarkText = $config['watermark_text'] ?? 'CERTIFIED TRANSLATION';
        
        // Save current state
        $pdf->SetAlpha(0.1);
        
        // Set watermark properties
        $pdf->SetFont('Arial', 'B', 60);
        $pdf->SetTextColor(200, 200, 200);
        
        // Calculate center position
        $x = $size['width'] / 2;
        $y = $size['height'] / 2;
        
        // Rotate and add text
        $pdf->StartTransform();
        $pdf->Rotate(45, $x, $y);
        $pdf->Text($x - 100, $y, $watermarkText);
        $pdf->StopTransform();
        
        // Restore alpha
        $pdf->SetAlpha(1);
    }

    /**
     * Draw text-based stamp
     * 
     * @param Fpdi $pdf
     * @param array $coordinates
     * @param array $dimensions
     * @param array $config
     */
    protected function drawTextStamp(Fpdi $pdf, array $coordinates, array $dimensions, array $config): void
    {
        // Draw rectangle border
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.5);
        $pdf->Rect(
            $coordinates['x'],
            $coordinates['y'],
            $dimensions['width'],
            $dimensions['height']
        );

        // Add stamp text
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);
        
        $stampText = $config['stamp_text'] ?? 'CERTIFIED';
        $textX = $coordinates['x'] + ($dimensions['width'] / 2) - 10;
        $textY = $coordinates['y'] + ($dimensions['height'] / 2);
        
        $pdf->Text($textX, $textY, $stampText);
    }

    /**
     * Add text below/above stamp
     * 
     * @param Fpdi $pdf
     * @param array $coordinates
     * @param array $dimensions
     * @param string $text
     */
    protected function addStampText(Fpdi $pdf, array $coordinates, array $dimensions, string $text): void
    {
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetTextColor(50, 50, 50);
        
        $textY = $coordinates['y'] + $dimensions['height'] + 3;
        $textX = $coordinates['x'];
        
        $pdf->Text($textX, $textY, $text);
    }

    /**
     * Add QR code near stamp
     * 
     * @param Fpdi $pdf
     * @param array $coordinates
     * @param array $dimensions
     * @param string $qrCodePath
     */
    protected function addQRCodeToStamp(Fpdi $pdf, array $coordinates, array $dimensions, string $qrCodePath): void
    {
        if (Storage::exists($qrCodePath)) {
            $fullPath = Storage::path($qrCodePath);
            
            // Add QR code next to stamp
            $qrX = $coordinates['x'] - 15;
            $qrY = $coordinates['y'];
            
            $pdf->Image($fullPath, $qrX, $qrY, 12, 12);
        }
    }

    /**
     * Get stamp image path
     * 
     * @param string $type
     * @param array $config
     * @return string|null
     */
    protected function getStampImage(string $type, array $config): ?string
    {
        // Check if custom stamp provided
        if (isset($config['stamp_image_path'])) {
            $path = Storage::path($config['stamp_image_path']);
            if (file_exists($path)) {
                return $path;
            }
        }

        // Generate SVG stamp and convert to PNG
        $svgContent = $this->generateStampSVG($type, $config);
        
        if ($svgContent) {
            // Save SVG temporarily
            $tempSvgPath = storage_path('app/temp/stamp_' . uniqid() . '.svg');
            Storage::put('temp/stamp_' . uniqid() . '.svg', $svgContent);
            
            return Storage::path('temp/stamp_' . uniqid() . '.svg');
        }

        return null;
    }

    /**
     * Generate stamp SVG
     * 
     * @param string $type
     * @param array $config
     * @return string
     */
    protected function generateStampSVG(string $type, array $config): string
    {
        $color = $config['stamp_color'] ?? '#1a56db';
        $text = $config['stamp_text'] ?? 'CERTIFIED';
        $subtext = $config['stamp_subtext'] ?? date('Y-m-d');

        return <<<SVG
<svg width="150" height="150" xmlns="http://www.w3.org/2000/svg">
    <circle cx="75" cy="75" r="70" fill="none" stroke="{$color}" stroke-width="3"/>
    <circle cx="75" cy="75" r="60" fill="none" stroke="{$color}" stroke-width="1"/>
    <text x="75" y="70" font-family="Arial" font-size="20" font-weight="bold" 
          fill="{$color}" text-anchor="middle">{$text}</text>
    <text x="75" y="90" font-family="Arial" font-size="10" 
          fill="{$color}" text-anchor="middle">{$subtext}</text>
</svg>
SVG;
    }

    /**
     * Should stamp be applied on this page?
     * 
     * @param int $pageNo
     * @param int $totalPages
     * @param array $config
     * @return bool
     */
    protected function shouldApplyStampOnPage(int $pageNo, int $totalPages, array $config): bool
    {
        $applyOn = $config['apply_on'] ?? 'all';

        return match($applyOn) {
            'first' => $pageNo === 1,
            'last' => $pageNo === $totalPages,
            'first_and_last' => $pageNo === 1 || $pageNo === $totalPages,
            'all' => true,
            default => true
        };
    }

    /**
     * Get stamp dimensions based on size
     * 
     * @param string $size
     * @return array
     */
    protected function getStampDimensions(string $size): array
    {
        return match($size) {
            'small' => ['width' => 20, 'height' => 20],
            'medium' => ['width' => 30, 'height' => 30],
            'large' => ['width' => 40, 'height' => 40],
            default => ['width' => 30, 'height' => 30]
        };
    }

    /**
     * Calculate stamp position on page
     * 
     * @param string $position
     * @param array $pageSize
     * @param array $stampDimensions
     * @return array
     */
    protected function calculateStampPosition(string $position, array $pageSize, array $stampDimensions): array
    {
        $margin = 10;

        return match($position) {
            'top_left' => [
                'x' => $margin,
                'y' => $margin
            ],
            'top_right' => [
                'x' => $pageSize['width'] - $stampDimensions['width'] - $margin,
                'y' => $margin
            ],
            'bottom_left' => [
                'x' => $margin,
                'y' => $pageSize['height'] - $stampDimensions['height'] - $margin
            ],
            'bottom_right' => [
                'x' => $pageSize['width'] - $stampDimensions['width'] - $margin,
                'y' => $pageSize['height'] - $stampDimensions['height'] - $margin
            ],
            'center' => [
                'x' => ($pageSize['width'] - $stampDimensions['width']) / 2,
                'y' => ($pageSize['height'] - $stampDimensions['height']) / 2
            ],
            default => [
                'x' => $pageSize['width'] - $stampDimensions['width'] - $margin,
                'y' => $pageSize['height'] - $stampDimensions['height'] - $margin
            ]
        };
    }

    /**
     * Generate stamped filename
     * 
     * @param string $originalPath
     * @return string
     */
    protected function generateStampedFilename(string $originalPath): string
    {
        $pathInfo = pathinfo($originalPath);
        return $pathInfo['filename'] . '_stamped.' . $pathInfo['extension'];
    }

    /**
     * Apply multiple stamps to PDF
     * 
     * @param string $pdfPath
     * @param array $stamps Array of stamp configurations
     * @return array
     */
    public function applyMultipleStamps(string $pdfPath, array $stamps): array
    {
        $currentPath = $pdfPath;
        $results = [];

        foreach ($stamps as $index => $stampConfig) {
            $result = $this->applyStamp($currentPath, $stampConfig);
            
            if ($result['success']) {
                $currentPath = $result['stamped_path'];
                $results[] = $result;
            } else {
                return [
                    'success' => false,
                    'error' => "Failed to apply stamp #{$index}",
                    'partial_results' => $results
                ];
            }
        }

        return [
            'success' => true,
            'final_path' => $currentPath,
            'stamps_applied' => count($results),
            'details' => $results
        ];
    }
}
