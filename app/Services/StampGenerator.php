<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StampGenerator
{
    /**
     * إنشاء ختم مع البيانات الديناميكية
     */
    public static function generate($certNumber, $date, $verificationUrl)
    {
        // مسار الختم الأساسي (بدون نصوص)
        $baseStampPath = public_path('images/certified-translation/stamp_with_platform_name.png');
        
        // إنشاء صورة جديدة
        $stamp = Image::make($baseStampPath);
        
        // إنشاء QR code
        $qrPath = storage_path('app/temp/qr_' . $certNumber . '.png');
        QrCode::format('png')
            ->size(400)
            ->margin(0)
            ->generate($verificationUrl, $qrPath);
        
        // إضافة QR في المنتصف
        $qr = Image::make($qrPath);
        $qr->resize(300, 300);
        
        // حساب موقع المنتصف
        $centerX = ($stamp->width() / 2) - ($qr->width() / 2);
        $centerY = ($stamp->height() / 2) - ($qr->height() / 2);
        
        $stamp->insert($qr, 'top-left', $centerX, $centerY);
        
        // إضافة رقم الشهادة أسفل QR
        $stamp->text('CERT. NO: ' . $certNumber, $stamp->width() / 2, $stamp->height() - 180, function($font) {
            $font->file(public_path('fonts/Arial.ttf'));
            $font->size(24);
            $font->color('#003366'); // Navy blue
            $font->align('center');
            $font->valign('middle');
        });
        
        // إضافة التاريخ
        $stamp->text('DATE: ' . $date, $stamp->width() / 2, $stamp->height() - 140, function($font) {
            $font->file(public_path('fonts/Arial.ttf'));
            $font->size(20);
            $font->color('#003366');
            $font->align('center');
            $font->valign('middle');
        });
        
        // حفظ الختم النهائي
        $finalPath = storage_path('app/temp/stamp_' . $certNumber . '.png');
        $stamp->save($finalPath);
        
        // حذف QR المؤقت
        @unlink($qrPath);
        
        return $finalPath;
    }
}
