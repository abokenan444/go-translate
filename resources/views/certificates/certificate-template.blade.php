<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Certificate of Translation</title>
    <style>
        @page { margin: 40px; }
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .certificate-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
            border: 3px solid #2563eb;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }
        .logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
        }
        .title {
            font-size: 32px;
            font-weight: bold;
            color: #2563eb;
            margin: 0;
        }
        .subtitle {
            font-size: 16px;
            color: #666;
            margin-top: 5px;
        }
        .body-content {
            margin: 30px 0;
            text-align: justify;
        }
        .details-section {
            background: #f8fafc;
            padding: 20px;
            border-left: 4px solid #2563eb;
            margin: 30px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .detail-label {
            font-weight: bold;
            color: #666;
        }
        .detail-value {
            color: #333;
        }
        .qr-section {
            text-align: center;
            margin: 40px 0;
        }
        .qr-code {
            width: 200px;
            height: 200px;
            margin: 0 auto;
        }
        .verification-url {
            font-size: 12px;
            color: #666;
            margin-top: 10px;
            word-break: break-all;
        }
        .stamps-section {
            display: flex;
            justify-content: space-around;
            margin: 40px 0;
        }
        .stamp {
            width: 150px;
            height: 150px;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .signature-line {
            border-top: 2px solid #333;
            width: 250px;
            margin: 40px auto 10px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Header -->
        <div class="header">
            <h1 class="title">CERTIFICATE OF TRANSLATION</h1>
            <p class="subtitle">CulturalTranslate - Certified Translation Platform</p>
        </div>

        <!-- Body Content -->
        <div class="body-content">
            <p>
                This is to certify that the attached document has been accurately translated from 
                <strong>{{ strtoupper($source_language) }}</strong> to <strong>{{ strtoupper($target_language) }}</strong> 
                by CulturalTranslate, a certified translation platform.
            </p>
            <p>
                The translation has been completed in accordance with international translation standards and cultural 
                adaptation guidelines. The translated content preserves the meaning, context, and legal implications 
                of the original document.
            </p>
        </div>

        <!-- Certificate Details -->
        <div class="details-section">
            <h3 style="margin-top: 0; color: #2563eb;">Certificate Details</h3>
            
            <div class="detail-row">
                <span class="detail-label">Certificate ID:</span>
                <span class="detail-value">{{ $certificate_id }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Serial Number:</span>
                <span class="detail-value">{{ $serial_number }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Issue Date:</span>
                <span class="detail-value">{{ $issue_date }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Document Type:</span>
                <span class="detail-value">{{ $document_type }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Original Filename:</span>
                <span class="detail-value">{{ $original_filename }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Page Count:</span>
                <span class="detail-value">{{ $page_count }}</span>
            </div>

            @if($word_count > 0)
            <div class="detail-row">
                <span class="detail-label">Word Count:</span>
                <span class="detail-value">{{ number_format($word_count) }}</span>
            </div>
            @endif

            @if($translator)
            <div class="detail-row">
                <span class="detail-label">Translator:</span>
                <span class="detail-value">{{ $translator['name'] }}</span>
            </div>
            @endif

            @if($partner)
            <div class="detail-row">
                <span class="detail-label">Certified Partner:</span>
                <span class="detail-value">{{ $partner['name'] }}</span>
            </div>
            @endif
        </div>

        <!-- QR Code Section -->
        <div class="qr-section">
            <h3 style="color: #2563eb;">Verify This Certificate</h3>
            <div class="qr-code">
                {!! $qr_code_svg !!}
            </div>
            <p class="verification-url">
                Scan QR code or visit:<br>
                <strong>{{ $verification_url }}</strong>
            </p>
            <p style="font-size: 12px; color: #666; margin-top: 10px;">
                This certificate can be verified at any time using the QR code above or by visiting our verification page.
            </p>
        </div>

        <!-- Stamps Section -->
        @if($platform_stamp || $partner_stamp)
        <div class="stamps-section">
            @if($platform_stamp)
            <div style="text-align: center;">
                <div class="stamp">
                    {!! $platform_stamp !!}
                </div>
                <p style="font-size: 11px; color: #666; margin-top: 10px;">CulturalTranslate Official Seal</p>
            </div>
            @endif

            @if($partner_stamp)
            <div style="text-align: center;">
                <div class="stamp">
                    {!! $partner_stamp !!}
                </div>
                <p style="font-size: 11px; color: #666; margin-top: 10px;">Certified Partner Seal</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Signature -->
        <div style="text-align: center;">
            <div class="signature-line"></div>
            <p style="font-size: 14px; color: #666;">
                <strong>Digital Signature</strong><br>
                CulturalTranslate Platform
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>CulturalTranslate</strong><br>
                Global Authority for Cultural Intelligence and Certified Communication<br>
                www.culturaltranslate.com | support@culturaltranslate.com
            </p>
            <p style="margin-top: 15px;">
                This certificate is issued under the authority of CulturalTranslate Platform<br>
                and is valid for official use worldwide.
            </p>
            <p style="margin-top: 10px; font-size: 10px;">
                © {{ date('Y') }} CulturalTranslate. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
