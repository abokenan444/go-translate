<?php

namespace App\Services;

use Smalot\PdfParser\Parser as PdfParser;
use TCPDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\Facades\Image;

class PDFProcessor
{
    /**
     * استخراج النص من PDF
     */
    public static function extractText($pdfPath)
    {
        try {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($pdfPath);
            $text = $pdf->getText();
            
            return trim($text);
        } catch (\Exception $e) {
            \Log::error('PDF extraction error: ' . $e->getMessage());
            throw new \Exception('Failed to extract text from PDF');
        }
    }
    
    /**
     * Estimate number of pages in a PDF file.
     */
    public static function estimatePages(string $pdfPath): int
    {
        try {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($pdfPath);
            return count($pdf->getPages());
        } catch (\Exception $e) {
            \Log::error('PDF page count error: ' . $e->getMessage());
            return 1; // Default to 1 page if error
        }
    }
    
    /**
     * استخراج النص من صورة باستخدام OCR
     */
    public static function extractTextFromImage($imagePath)
    {
        // يمكن استخدام Tesseract OCR أو Google Vision API
        // هنا نستخدم مثال بسيط
        try {
            // تثبيت: composer require thiagoalessio/tesseract_ocr
            $ocr = new \thiagoalessio\TesseractOCR\TesseractOCR($imagePath);
            $text = $ocr->run();
            
            return trim($text);
        } catch (\Exception $e) {
            \Log::error('OCR extraction error: ' . $e->getMessage());
            throw new \Exception('Failed to extract text from image');
        }
    }
    
    /**
     * إنشاء PDF معتمد مع الختم والـ QR
     */
    public static function createCertifiedPDF($data)
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');
        
        // إعدادات PDF
        $pdf->SetCreator('CulturalTranslate');
        $pdf->SetAuthor('CulturalTranslate');
        $pdf->SetTitle('Certified Translation - ' . $data['cert_number']);
        $pdf->SetSubject('Certified Document Translation');
        
        // إزالة الهيدر والفوتر الافتراضي
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // إضافة صفحة
        $pdf->AddPage();
        
        // إضافة الختم في الأعلى
        self::addStampToPDF($pdf, $data);
        
        // إضافة معلومات الشهادة
        self::addCertificateInfo($pdf, $data);
        
        // إضافة النص المترجم
        self::addTranslatedText($pdf, $data['translated_text']);
        
        // إضافة الفوتر مع QR
        self::addFooterWithQR($pdf, $data);
        
        return $pdf->Output('', 'S'); // إرجاع كـ string
    }
    
    /**
     * إضافة الختم إلى PDF
     */
    private static function addStampToPDF($pdf, $data)
    {
        // إنشاء الختم مع البيانات الديناميكية
        $stampPath = StampGenerator::generate(
            $data['cert_number'],
            $data['date'],
            $data['verification_url']
        );
        
        // إضافة الختم في الزاوية اليمنى العليا
        $pdf->Image($stampPath, 150, 10, 40, 40, 'PNG');
    }
    
    /**
     * إضافة معلومات الشهادة
     */
    private static function addCertificateInfo($pdf, $data)
    {
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetTextColor(0, 51, 102); // Navy blue
        $pdf->Cell(0, 10, 'CERTIFIED TRANSLATION', 0, 1, 'C');
        
        $pdf->Ln(5);
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        
        $info = [
            'Certificate Number' => $data['cert_number'],
            'Document Type' => ucfirst($data['document_type']),
            'Original Filename' => $data['original_filename'],
            'Source Language' => strtoupper($data['source_language']),
            'Target Language' => strtoupper($data['target_language']),
            'Translation Date' => $data['date'],
        ];
        
        foreach ($info as $label => $value) {
            $pdf->Cell(60, 7, $label . ':', 0, 0, 'L');
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(0, 7, $value, 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 10);
        }
        
        $pdf->Ln(5);
        
        // خط فاصل
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(5);
    }
    
    /**
     * إضافة النص المترجم
     */
    private static function addTranslatedText($pdf, $text)
    {
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 7, 'Translated Content:', 0, 1, 'L');
        $pdf->Ln(2);
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->MultiCell(0, 5, $text, 0, 'L');
    }
    
    /**
     * إضافة الفوتر مع QR
     */
    private static function addFooterWithQR($pdf, $data)
    {
        // الانتقال إلى أسفل الصفحة
        $pdf->SetY(-40);
        
        // خط فاصل
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(2);
        
        // إنشاء QR code
        $qrPath = storage_path('app/temp/qr_' . $data['cert_number'] . '.png');
        QrCode::format('png')
            ->size(300)
            ->generate($data['verification_url'], $qrPath);
        
        // إضافة QR code
        $pdf->Image($qrPath, 15, $pdf->GetY(), 25, 25, 'PNG');
        
        // إضافة نص التحقق
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetX(45);
        $pdf->MultiCell(0, 4, 
            "This is a certified translation issued by CulturalTranslate.\n" .
            "Scan the QR code or visit: " . $data['verification_url'] . "\n" .
            "to verify the authenticity of this document.",
            0, 'L');
        
        // حذف QR المؤقت
        @unlink($qrPath);
    }

    /**
     * إنشاء PDF مترجم مع الختم والباركود
     */
    public static function createTranslatedPDF(
        string $originalPath,
        string $outputPath,
        string $translatedText,
        string $trackingNumber,
        array $metadata
    ): void {
        try {
            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');
            
            // إعدادات PDF
            $pdf->SetCreator('Cultural Translate Platform');
            $pdf->SetAuthor('Cultural Translate Platform');
            $pdf->SetTitle('Certified Translation - ' . $trackingNumber);
            $pdf->SetSubject('Legal Document Translation');
            
            // إزالة الهيدر والفوتر الافتراضي
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            
            // إضافة صفحة
            $pdf->AddPage();
            
            // إضافة الختم الرسمي في الزاوية اليمنى العليا
            self::addOfficialStamp($pdf, $trackingNumber, $metadata);
            
            // إضافة معلومات الوثيقة
            self::addDocumentInfo($pdf, $trackingNumber, $metadata);
            
            // إضافة النص المترجم
            $pdf->SetFont('helvetica', '', 11);
            $pdf->MultiCell(0, 6, $translatedText, 0, 'L');
            
            // إضافة الفوتر مع الباركود
            self::addFooterWithBarcode($pdf, $trackingNumber, $metadata);
            
            // حفظ الملف
            $pdf->Output($outputPath, 'F');
            
        } catch (\Exception $e) {
            \Log::error('PDF creation error: ' . $e->getMessage());
            throw new \Exception('Failed to create translated PDF: ' . $e->getMessage());
        }
    }

    /**
     * إضافة الختم الرسمي
     */
    private static function addOfficialStamp($pdf, string $trackingNumber, array $metadata): void
    {
        // رسم دائرة الختم
        $pdf->SetLineWidth(1.5);
        $pdf->SetDrawColor(0, 51, 153); // Navy blue
        $pdf->Circle(175, 25, 20, 0, 360, 'D');
        
        // النص الخارجي للختم
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetTextColor(0, 51, 153);
        
        // "CERTIFIED TRANSLATION" في الأعلى
        $pdf->SetXY(155, 10);
        $pdf->Cell(40, 5, 'CERTIFIED TRANSLATION', 0, 0, 'C');
        
        // "CULTURAL TRANSLATE" في الوسط
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetXY(155, 22);
        $pdf->Cell(40, 5, 'CULTURAL', 0, 0, 'C');
        $pdf->SetXY(155, 27);
        $pdf->Cell(40, 5, 'TRANSLATE', 0, 0, 'C');
        
        // التاريخ في الأسفل
        $pdf->SetFont('helvetica', '', 7);
        $pdf->SetXY(155, 38);
        $pdf->Cell(40, 5, date('Y-m-d'), 0, 0, 'C');
        
        // رسم نجمة صغيرة في المنتصف
        $pdf->SetDrawColor(255, 215, 0); // Gold
        $pdf->SetFillColor(255, 215, 0);
        $pdf->Star(175, 25, 3, 6, 3, 0, 0, 'DF');
        
        // إعادة تعيين اللون
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
    }

    /**
     * إضافة معلومات الوثيقة
     */
    private static function addDocumentInfo($pdf, string $trackingNumber, array $metadata): void
    {
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(0, 51, 102);
        $pdf->Cell(0, 12, 'CERTIFIED LEGAL TRANSLATION', 0, 1, 'C');
        
        $pdf->Ln(3);
        
        // خط فاصل
        $pdf->SetDrawColor(0, 51, 102);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(5);
        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetTextColor(0, 0, 0);
        
        $info = [
            'Tracking Number' => $trackingNumber,
            'Document Type' => ucfirst($metadata['document_type'] ?? 'Legal Document'),
            'Source Language' => strtoupper($metadata['source_language'] ?? 'EN'),
            'Target Language' => strtoupper($metadata['target_language'] ?? 'AR'),
            'Translation Date' => $metadata['translation_date'] ?? date('Y-m-d H:i:s'),
            'Certified By' => $metadata['translator'] ?? 'Cultural Translate Platform',
        ];
        
        foreach ($info as $label => $value) {
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Cell(55, 6, $label . ':', 0, 0, 'L');
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 6, $value, 0, 1, 'L');
        }
        
        $pdf->Ln(3);
        
        // خط فاصل
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->SetLineWidth(0.3);
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(5);
        
        // عنوان النص المترجم
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetTextColor(0, 51, 102);
        $pdf->Cell(0, 7, 'Translated Content:', 0, 1, 'L');
        $pdf->Ln(2);
        
        // إعادة تعيين اللون
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetDrawColor(0, 0, 0);
    }

    /**
     * إضافة الفوتر مع الباركود
     */
    private static function addFooterWithBarcode($pdf, string $trackingNumber, array $metadata): void
    {
        // الانتقال إلى أسفل الصفحة
        $pdf->SetY(-45);
        
        // خط فاصل
        $pdf->SetDrawColor(0, 51, 102);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(3);
        
        // إنشاء QR code للتتبع
        $verificationUrl = 'https://culturaltranslate.com/verify/' . $trackingNumber;
        $qrPath = storage_path('app/temp/qr_' . $trackingNumber . '.png');
        
        // التأكد من وجود المجلد
        if (!file_exists(dirname($qrPath))) {
            mkdir(dirname($qrPath), 0775, true);
        }
        
        QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate($verificationUrl, $qrPath);
        
        // إضافة QR code على اليسار
        $pdf->Image($qrPath, 15, $pdf->GetY(), 30, 30, 'PNG');
        
        // إضافة معلومات التحقق على اليمين
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetX(50);
        $pdf->Cell(0, 5, 'Document Verification', 0, 1, 'L');
        
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetX(50);
        $pdf->MultiCell(0, 4, 
            "This is a certified legal translation issued by Cultural Translate Platform.\n" .
            "Scan the QR code or visit the following URL to verify authenticity:\n" .
            $verificationUrl . "\n\n" .
            "This document is legally certified and can be used for official purposes.\n" .
            "For inquiries, contact: support@culturaltranslate.com",
            0, 'L');
        
        // حذف QR المؤقت
        @unlink($qrPath);
    }
}
