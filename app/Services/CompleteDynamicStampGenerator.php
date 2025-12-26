<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class CompleteDynamicStampGenerator
{
    /**
     * Generate a complete dynamic certification stamp with QR code and date
     * 
     * @param string $qrCodeUrl The URL to encode in QR code
     * @param string $date The certification date
     * @param string $certificateId The certificate ID
     * @return string Path to generated stamp image
     */
    public function generate(string $qrCodeUrl, string $date, string $certificateId): string
    {
        // Stamp dimensions
        $size = 600;
        $centerX = $size / 2;
        $centerY = $size / 2;
        
        // Create image
        $stamp = imagecreatetruecolor($size, $size);
        
        // Enable alpha blending
        imagealphablending($stamp, false);
        imagesavealpha($stamp, true);
        
        // Colors
        $transparent = imagecolorallocatealpha($stamp, 0, 0, 0, 127);
        $navy = imagecolorallocate($stamp, 25, 42, 86);
        $gold = imagecolorallocate($stamp, 212, 175, 55);
        $white = imagecolorallocate($stamp, 255, 255, 255);
        $lightGold = imagecolorallocate($stamp, 255, 215, 0);
        
        // Fill with transparent background
        imagefill($stamp, 0, 0, $transparent);
        imagealphablending($stamp, true);
        
        // Draw outer gold circle (decorative border)
        $this->drawCircle($stamp, $centerX, $centerY, 280, $gold, 12);
        
        // Draw middle decorative circle
        $this->drawCircle($stamp, $centerX, $centerY, 260, $lightGold, 3);
        
        // Draw main navy circle
        imagefilledellipse($stamp, $centerX, $centerY, 500, 500, $navy);
        
        // Draw inner gold circle
        $this->drawCircle($stamp, $centerX, $centerY, 240, $gold, 8);
        
        // Draw decorative inner circle
        $this->drawCircle($stamp, $centerX, $centerY, 220, $lightGold, 2);
        
        // Add curved text "CERTIFIED TRANSLATION" at top
        $this->addCurvedText($stamp, "CERTIFIED TRANSLATION", $centerX, $centerY - 60, 180, $gold, 18);
        
        // Add curved text "OFFICIALLY CERTIFIED" at bottom  
        $this->addCurvedText($stamp, "OFFICIALLY CERTIFIED", $centerX, $centerY + 140, 140, $gold, 16);
        
        // Generate QR code
        $qrCode = new QrCode($qrCodeUrl);
        $qrCode->setSize(120);
        $qrCode->setMargin(0);
        
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        
        // Save QR code temporarily
        $qrTempPath = sys_get_temp_dir() . '/qr_' . uniqid() . '.png';
        file_put_contents($qrTempPath, $result->getString());
        
        // Load QR code image
        $qrImage = imagecreatefrompng($qrTempPath);
        
        // Add QR code to center
        $qrSize = 120;
        imagecopyresampled(
            $stamp, $qrImage,
            $centerX - $qrSize/2, $centerY - 30,
            0, 0,
            $qrSize, $qrSize,
            imagesx($qrImage), imagesy($qrImage)
        );
        
        imagedestroy($qrImage);
        unlink($qrTempPath);
        
        // Add date below QR code
        $this->addText($stamp, "DATE: " . $date, $centerX, $centerY + 70, $white, 12);
        
        // Add certificate ID at bottom
        $this->addText($stamp, $certificateId, $centerX, $centerY + 95, $lightGold, 10);
        
        // Add decorative stars
        $this->addStar($stamp, $centerX - 100, $centerY - 80, 15, $lightGold);
        $this->addStar($stamp, $centerX + 100, $centerY - 80, 15, $lightGold);
        
        // Save stamp
        $stampPath = storage_path('app/stamps/dynamic_stamp_' . time() . '_' . uniqid() . '.png');
        $stampDir = dirname($stampPath);
        if (!is_dir($stampDir)) {
            mkdir($stampDir, 0755, true);
        }
        
        imagepng($stamp, $stampPath);
        imagedestroy($stamp);
        
        return $stampPath;
    }
    
    /**
     * Draw a circle outline
     */
    private function drawCircle($image, $cx, $cy, $diameter, $color, $thickness)
    {
        for ($i = 0; $i < $thickness; $i++) {
            imageellipse($image, $cx, $cy, $diameter - $i, $diameter - $i, $color);
        }
    }
    
    /**
     * Add curved text
     */
    private function addCurvedText($image, $text, $cx, $cy, $radius, $color, $fontSize)
    {
        $len = strlen($text);
        $angleStep = 3; // degrees between each character
        $startAngle = -($len * $angleStep) / 2;
        
        $font = '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf';
        if (!file_exists($font)) {
            $font = 5; // Use built-in font as fallback
        }
        
        for ($i = 0; $i < $len; $i++) {
            $angle = $startAngle + ($i * $angleStep);
            $radian = deg2rad($angle - 90);
            
            $x = $cx + ($radius * cos($radian));
            $y = $cy + ($radius * sin($radian));
            
            if (is_string($font)) {
                imagettftext($image, $fontSize, $angle, $x, $y, $color, $font, $text[$i]);
            } else {
                imagestring($image, $font, $x, $y, $text[$i], $color);
            }
        }
    }
    
    /**
     * Add centered text
     */
    private function addText($image, $text, $cx, $cy, $color, $fontSize)
    {
        $font = '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf';
        
        if (file_exists($font)) {
            $bbox = imagettfbbox($fontSize, 0, $font, $text);
            $textWidth = $bbox[2] - $bbox[0];
            $x = $cx - ($textWidth / 2);
            imagettftext($image, $fontSize, 0, $x, $cy, $color, $font, $text);
        } else {
            // Fallback to built-in font
            $textWidth = imagefontwidth(5) * strlen($text);
            $x = $cx - ($textWidth / 2);
            imagestring($image, 5, $x, $cy, $text, $color);
        }
    }
    
    /**
     * Draw a star
     */
    private function addStar($image, $cx, $cy, $size, $color)
    {
        $points = [];
        for ($i = 0; $i < 10; $i++) {
            $angle = deg2rad(($i * 36) - 90);
            $radius = ($i % 2 == 0) ? $size : $size / 2;
            $points[] = $cx + ($radius * cos($angle));
            $points[] = $cy + ($radius * sin($angle));
        }
        imagefilledpolygon($image, $points, 10, $color);
    }
}
