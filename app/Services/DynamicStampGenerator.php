<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Service for generating dynamic certification stamps with embedded QR code and date
 */
class DynamicStampGenerator
{
    /**
     * Generate a dynamic stamp with QR code and date
     *
     * @param string $qrCodePath Path to QR code image in storage
     * @param string $date Date string (e.g., "Dec 11, 2025")
     * @param string $certId Certificate ID
     * @return string Path to generated stamp in storage
     */
    public function generateStamp(string $qrCodePath, string $date, string $certId): string
    {
        // Load base stamp template
        $baseStampPath = storage_path('app/stamps/base_stamp_template.png');
        
        if (!file_exists($baseStampPath)) {
            throw new \RuntimeException("Base stamp template not found at: {$baseStampPath}");
        }
        
        $baseStamp = imagecreatefrompng($baseStampPath);
        if (!$baseStamp) {
            throw new \RuntimeException("Failed to load base stamp image");
        }
        
        $width = imagesx($baseStamp);
        $height = imagesy($baseStamp);
        
        // Create new image with transparency
        $stamp = imagecreatetruecolor($width, $height);
        imagesavealpha($stamp, true);
        $transparent = imagecolorallocatealpha($stamp, 0, 0, 0, 127);
        imagefill($stamp, 0, 0, $transparent);
        
        // Copy base stamp
        imagecopy($stamp, $baseStamp, 0, 0, 0, 0, $width, $height);
        
        // Load and add QR code
        $qrFullPath = storage_path('app/' . $qrCodePath);
        if (file_exists($qrFullPath)) {
            $qrCode = imagecreatefrompng($qrFullPath);
            if ($qrCode) {
                // Calculate QR code position (center of stamp)
                $qrSize = 200;
                $qrX = ($width - $qrSize) / 2;
                $qrY = ($height - $qrSize) / 2 - 50; // Slightly above center
                
                // Resize and place QR code
                imagecopyresampled($stamp, $qrCode, $qrX, $qrY, 0, 0, $qrSize, $qrSize, imagesx($qrCode), imagesy($qrCode));
                imagedestroy($qrCode);
            }
        }
        
        // Add date text
        $navy = imagecolorallocate($stamp, 25, 42, 86); // Navy blue
        $fontPath = '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf';
        
        if (file_exists($fontPath)) {
            // Position date below "DATE:" in the stamp
            $dateX = $width / 2 - 80;
            $dateY = $height - 280;
            
            // Add date
            imagettftext($stamp, 24, 0, $dateX, $dateY, $navy, $fontPath, $date);
        }
        
        // Save stamp
        $stampPath = 'stamps/dynamic/' . $certId . '.png';
        $stampFullPath = storage_path('app/' . $stampPath);
        
        // Ensure directory exists
        $stampDir = dirname($stampFullPath);
        if (!is_dir($stampDir)) {
            mkdir($stampDir, 0755, true);
        }
        
        imagepng($stamp, $stampFullPath);
        
        // Clean up
        imagedestroy($stamp);
        imagedestroy($baseStamp);
        
        Log::info('Dynamic stamp generated', [
            'cert_id' => $certId,
            'date' => $date,
            'stamp_path' => $stampPath,
        ]);
        
        return $stampPath;
    }
}
