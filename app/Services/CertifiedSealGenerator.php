<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class CertifiedSealGenerator
{
    /**
     * Generate a certified seal PNG with QR code and date using template
     */
    public function generatePng(string $trackingNumber, string $date, string $verificationUrl): string
    {
        // 1) Load template (if present). Avoid hardcoding /tmp; allow overriding.
        $templatePath = config('official_documents.seal.template_path', '/tmp/seal_template.png');
        $template = @imagecreatefrompng($templatePath);
        if (!$template) {
            // Fallback: create from scratch if template not found
            return $this->generatePngFromScratch($trackingNumber, $date, $verificationUrl);
        }
        
        // Get template dimensions
        $width = imagesx($template);
        $height = imagesy($template);
        
        // 2) Generate QR Code
        $qrCode = new QrCode($verificationUrl);
        $qrCode->setSize(min(180, (int)($width * 0.18))); // 18% of width
        $qrCode->setMargin(0);
        
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrImage = imagecreatefromstring($result->getString());
        
        // Enable alpha blending
        imagealphablending($template, true);
        imagesavealpha($template, true);
        
        // Define colors
        $blue = imagecolorallocate($template, 27, 51, 95);
        
        // 3) Add QR code in center
        $qrSize = imagesx($qrImage);
        $qrX = ($width - $qrSize) / 2;
        $qrY = ($height - $qrSize) / 2 - 40; // Slightly above center
        imagecopy($template, $qrImage, $qrX, $qrY, 0, 0, $qrSize, $qrSize);
        imagedestroy($qrImage);
        
        // 4) Add text below QR
        $font = '/usr/share/fonts/truetype/liberation/LiberationSerif-Bold.ttf';
        if (!file_exists($font)) {
            $font = '/usr/share/fonts/truetype/dejavu/DejaVuSerif-Bold.ttf';
        }
        
        $cx = $width / 2;
        
        // Date
        $dateY = $qrY + $qrSize + 35;
        $this->drawCenteredText($template, $date, $cx, $dateY, 24, $blue, $font);
        
        // Platform name
        $platformY = $dateY + 25;
        $this->drawCenteredText($template, 'culturaltranslate.com', $cx, $platformY, 18, $blue, $font);
        
        // Trim transparent margins (prevents oversized boxes when embedded into PDFs)
        $template = $this->trimTransparent($template);

        // 5) Save PNG
        $pngRelative = 'legal-documents/seals/' . $trackingNumber . '.png';
        $pngFullPath = storage_path('app/' . $pngRelative);
        
        $pngDir = dirname($pngFullPath);
        if (!is_dir($pngDir)) {
            mkdir($pngDir, 0755, true);
        }
        
        imagepng($template, $pngFullPath, 9);
        imagedestroy($template);
        
        return $pngRelative;
    }
    
    /**
     * Draw centered text
     */
    private function drawCenteredText($image, $text, $x, $y, $size, $color, $font)
    {
        $bbox = imagettfbbox($size, 0, $font, $text);
        $textWidth = $bbox[2] - $bbox[0];
        $textX = $x - ($textWidth / 2);
        
        imagettftext($image, $size, 0, $textX, $y, $color, $font, $text);
    }
    
    /**
     * Fallback: Generate from scratch if template not available
     */
    private function generatePngFromScratch(string $trackingNumber, string $date, string $verificationUrl): string
    {
        // Create seal image (smaller than 1000x1000 to keep PDFs light)
        $width = 600;
        $height = 600;
        $seal = imagecreatetruecolor($width, $height);
        
        // Enable alpha blending
        imagealphablending($seal, true);
        imagesavealpha($seal, true);
        
        // Define colors
        $transparent = imagecolorallocatealpha($seal, 0, 0, 0, 127);
        $gold1 = imagecolorallocate($seal, 251, 231, 180);
        $gold2 = imagecolorallocate($seal, 225, 182, 90);
        $blue1 = imagecolorallocate($seal, 27, 51, 95);
        $beige1 = imagecolorallocate($seal, 253, 240, 208);
        
        // Fill with transparent background
        imagefill($seal, 0, 0, $transparent);
        
        $cx = (int)($width / 2);
        $cy = (int)($height / 2);
        
        // Draw circles
        // Scale the drawing proportionally to the new canvas size
        $this->drawCircle($seal, $cx, $cy, 294, $gold1, 14);
        imagefilledellipse($seal, $cx, $cy, 540, 540, $blue1);
        $this->drawCircle($seal, $cx, $cy, 270, $gold2, 12);
        $this->drawDottedCircle($seal, $cx, $cy, 234, $gold1, 6);
        $this->drawCircle($seal, $cx, $cy, 168, $gold1, 8);
        imagefilledellipse($seal, $cx, $cy, 252, 252, $beige1);
        $this->drawCircle($seal, $cx, $cy, 126, $gold2, 6);
        
        // Generate QR Code
        $qrCode = new QrCode($verificationUrl);
        $qrCode->setSize(140);
        $qrCode->setMargin(0);
        
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrImage = imagecreatefromstring($result->getString());
        
        // Add QR code (centered)
        $qrX = $cx - 70;
        $qrY = $cy - 95;
        imagecopy($seal, $qrImage, $qrX, $qrY, 0, 0, 140, 140);
        imagedestroy($qrImage);
        
        // Add text
        $font = '/usr/share/fonts/truetype/liberation/LiberationSerif-Bold.ttf';
        if (!file_exists($font)) {
            $font = '/usr/share/fonts/truetype/dejavu/DejaVuSerif-Bold.ttf';
        }
        
        $this->drawCenteredText($seal, $date, $cx, $cy + 95, 16, $blue1, $font);
        $this->drawCenteredText($seal, 'culturaltranslate.com', $cx, $cy + 115, 12, $blue1, $font);
        
        // Add curved text
        $this->drawCurvedText($seal, 'CERTIFIED TRANSLATION', $cx, $cy, 264, 180, 30, $gold1, true, $font);
        $this->drawCurvedText($seal, 'OFFICIALLY CERTIFIED', $cx, $cy, 264, 0, 28, $gold1, false, $font);

        // Trim transparent margins
        $seal = $this->trimTransparent($seal);
        
        // Save PNG
        $pngRelative = 'legal-documents/seals/' . $trackingNumber . '.png';
        $pngFullPath = storage_path('app/' . $pngRelative);
        
        $pngDir = dirname($pngFullPath);
        if (!is_dir($pngDir)) {
            mkdir($pngDir, 0755, true);
        }
        
        imagepng($seal, $pngFullPath, 9);
        imagedestroy($seal);
        
        return $pngRelative;
    }
    
    private function drawCircle($image, $cx, $cy, $radius, $color, $thickness)
    {
        for ($i = 0; $i < $thickness; $i++) {
            imagearc($image, $cx, $cy, ($radius - $i) * 2, ($radius - $i) * 2, 0, 360, $color);
        }
    }
    
    private function drawDottedCircle($image, $cx, $cy, $radius, $color, $thickness)
    {
        $segments = 100;
        for ($i = 0; $i < $segments; $i++) {
            if ($i % 2 == 0) {
                $angle1 = ($i / $segments) * 2 * M_PI;
                $angle2 = (($i + 0.6) / $segments) * 2 * M_PI;
                
                $x1 = $cx + $radius * cos($angle1);
                $y1 = $cy + $radius * sin($angle1);
                $x2 = $cx + $radius * cos($angle2);
                $y2 = $cy + $radius * sin($angle2);
                
                imagesetthickness($image, $thickness);
                imageline($image, $x1, $y1, $x2, $y2, $color);
                imagesetthickness($image, 1);
            }
        }
    }
    
    private function drawCurvedText($image, $text, $cx, $cy, $radius, $startAngle, $fontSize, $color, $top, $font)
    {
        $len = strlen($text);
        $angleStep = 1.5;
        
        if ($top) {
            $currentAngle = $startAngle - (($len * $angleStep) / 2);
        } else {
            $currentAngle = $startAngle + (($len * $angleStep) / 2);
            $angleStep = -$angleStep;
        }
        
        for ($i = 0; $i < $len; $i++) {
            $char = $text[$i];
            
            if ($char == ' ') {
                $currentAngle += $angleStep * 0.5;
                continue;
            }
            
            $angleRad = deg2rad($currentAngle);
            $x = $cx + $radius * cos($angleRad);
            $y = $cy + $radius * sin($angleRad);
            
            $rotation = $currentAngle + 90;
            if (!$top) {
                $rotation = $currentAngle - 90;
            }
            
            imagettftext($image, $fontSize, $rotation, $x, $y, $color, $font, $char);
            
            $currentAngle += $angleStep;
        }
    }

    /**
     * Trim transparent margins around a PNG (helps avoid "huge" visual stamp boxes in PDFs).
     */
    private function trimTransparent($im)
    {
        $w = imagesx($im);
        $h = imagesy($im);
        $minX = $w; $minY = $h; $maxX = 0; $maxY = 0;

        // Scan pixels to find non-transparent bounds (fast enough for seal sizes)
        for ($y = 0; $y < $h; $y++) {
            for ($x = 0; $x < $w; $x++) {
                $rgba = imagecolorat($im, $x, $y);
                $alpha = ($rgba & 0x7F000000) >> 24;
                if ($alpha < 127) {
                    if ($x < $minX) $minX = $x;
                    if ($y < $minY) $minY = $y;
                    if ($x > $maxX) $maxX = $x;
                    if ($y > $maxY) $maxY = $y;
                }
            }
        }

        // If fully transparent or no bounds, return original
        if ($minX > $maxX || $minY > $maxY) {
            return $im;
        }

        $cropW = $maxX - $minX + 1;
        $cropH = $maxY - $minY + 1;
        $out = imagecreatetruecolor($cropW, $cropH);
        imagealphablending($out, false);
        imagesavealpha($out, true);
        $transparent = imagecolorallocatealpha($out, 0, 0, 0, 127);
        imagefill($out, 0, 0, $transparent);
        imagecopy($out, $im, 0, 0, $minX, $minY, $cropW, $cropH);
        imagedestroy($im);
        return $out;
    }
    
    /**
     * Generate pure SVG seal with embedded QR code
     * 
     * Layer 4: Certification Engine - Dynamic SVG seal generation
     * This creates a scalable, embeddable SVG seal that can be used in PDFs
     * and digital certificates without quality loss.
     */
    public function generateSvg(string $trackingNumber, string $date, string $verificationUrl): string
    {
        // Generate QR code as base64 data URI for embedding
        $qrCode = new QrCode($verificationUrl);
        $qrCode->setSize(200);
        $qrCode->setMargin(0);
        
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrDataUri = 'data:image/png;base64,' . base64_encode($result->getString());
        
        // Create pure SVG with embedded QR
        $svg = <<<SVG
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<svg width="600" height="600" viewBox="0 0 600 600" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
  <defs>
    <!-- Gradient definitions for professional look -->
    <radialGradient id="goldGradient" cx="50%" cy="50%" r="50%">
      <stop offset="0%" style="stop-color:#fbe7b4;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#e1b65a;stop-opacity:1" />
    </radialGradient>
    <radialGradient id="blueGradient" cx="50%" cy="50%" r="50%">
      <stop offset="0%" style="stop-color:#2d4a7c;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#1b335f;stop-opacity:1" />
    </radialGradient>
  </defs>
  
  <!-- Outer gold ring -->
  <circle cx="300" cy="300" r="294" fill="none" stroke="#fbe7b4" stroke-width="14"/>
  
  <!-- Blue background circle -->
  <circle cx="300" cy="300" r="270" fill="url(#blueGradient)"/>
  
  <!-- Inner gold ring -->
  <circle cx="300" cy="300" r="270" fill="none" stroke="#e1b65a" stroke-width="12"/>
  
  <!-- Dotted decorative circle -->
  <circle cx="300" cy="300" r="234" fill="none" stroke="#fbe7b4" stroke-width="3" stroke-dasharray="5,5"/>
  
  <!-- Inner gold frame -->
  <circle cx="300" cy="300" r="168" fill="none" stroke="#fbe7b4" stroke-width="8"/>
  
  <!-- Beige center background -->
  <circle cx="300" cy="300" r="126" fill="#fdf0d0"/>
  
  <!-- Inner decorative ring -->
  <circle cx="300" cy="300" r="126" fill="none" stroke="#e1b65a" stroke-width="6"/>
  
  <!-- QR Code (embedded as image) -->
  <image x="230" y="155" width="140" height="140" xlink:href="{$qrDataUri}"/>
  
  <!-- Tracking number and date -->
  <text x="300" y="325" font-family="Liberation Serif, DejaVu Serif, serif" font-size="16" font-weight="bold" fill="#1b335f" text-anchor="middle">
    {$date}
  </text>
  
  <!-- Platform name -->
  <text x="300" y="345" font-family="Liberation Serif, DejaVu Serif, serif" font-size="12" font-weight="bold" fill="#1b335f" text-anchor="middle">
    culturaltranslate.com
  </text>
  
  <!-- Tracking number -->
  <text x="300" y="365" font-family="Liberation Serif, DejaVu Serif, serif" font-size="10" fill="#1b335f" text-anchor="middle">
    ID: {$trackingNumber}
  </text>
  
  <!-- Top curved text: CERTIFIED TRANSLATION -->
  <path id="topCurve" d="M 100,300 A 200,200 0 0,1 500,300" fill="none"/>
  <text font-family="Liberation Serif, DejaVu Serif, serif" font-size="24" font-weight="bold" fill="#fbe7b4" text-anchor="middle">
    <textPath xlink:href="#topCurve" startOffset="50%">
      CERTIFIED TRANSLATION
    </textPath>
  </text>
  
  <!-- Bottom curved text: OFFICIALLY CERTIFIED -->
  <path id="bottomCurve" d="M 500,300 A 200,200 0 0,1 100,300" fill="none"/>
  <text font-family="Liberation Serif, DejaVu Serif, serif" font-size="22" font-weight="bold" fill="#fbe7b4" text-anchor="middle">
    <textPath xlink:href="#bottomCurve" startOffset="50%">
      OFFICIALLY CERTIFIED
    </textPath>
  </text>
  
  <!-- Verification stamp -->
  <rect x="230" y="380" width="140" height="25" rx="5" fill="#1b335f" opacity="0.1"/>
  <text x="300" y="397" font-family="Liberation Serif, DejaVu Serif, serif" font-size="9" fill="#1b335f" text-anchor="middle">
    SCAN TO VERIFY AUTHENTICITY
  </text>
</svg>
SVG;

        // Save SVG file
        $svgRelative = 'legal-documents/seals/' . $trackingNumber . '.svg';
        $svgFullPath = storage_path('app/' . $svgRelative);
        
        $svgDir = dirname($svgFullPath);
        if (!is_dir($svgDir)) {
            mkdir($svgDir, 0755, true);
        }
        
        file_put_contents($svgFullPath, $svg);
        
        return $svgRelative;
    }
    
    /**
     * Generate both PNG and SVG seals
     * 
     * Returns paths to both formats for maximum compatibility
     */
    public function generateBothFormats(string $trackingNumber, string $date, string $verificationUrl): array
    {
        return [
            'png' => $this->generatePng($trackingNumber, $date, $verificationUrl),
            'svg' => $this->generateSvg($trackingNumber, $date, $verificationUrl),
            'tracking_number' => $trackingNumber,
            'date' => $date,
            'verification_url' => $verificationUrl,
        ];
    }
}
