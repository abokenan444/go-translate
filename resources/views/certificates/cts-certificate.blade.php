<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CTS Certificate - {{ $certificate->certificate_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            color: #1a202c;
            line-height: 1.6;
            padding: 40px;
        }
        
        .certificate-container {
            border: 8px solid #2c5282;
            padding: 40px;
            background: #ffffff;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #4299e1;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #1a202c;
            font-size: 32px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .header .subtitle {
            color: #4a5568;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .header .standard {
            color: #2c5282;
            font-size: 14px;
            font-weight: bold;
        }
        
        .cts-badge {
            text-align: center;
            margin: 30px 0;
        }
        
        .cts-badge .level {
            display: inline-block;
            padding: 15px 40px;
            font-size: 36px;
            font-weight: bold;
            border-radius: 8px;
            color: #ffffff;
            @if($certificate->cts_level === 'CTS-A')
                background-color: #38a169;
            @elseif($certificate->cts_level === 'CTS-B')
                background-color: #3182ce;
            @elseif($certificate->cts_level === 'CTS-C')
                background-color: #d69e2e;
            @else
                background-color: #e53e3e;
            @endif
        }
        
        .cts-badge .description {
            margin-top: 10px;
            font-size: 14px;
            color: #4a5568;
        }
        
        .info-section {
            margin: 30px 0;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            padding: 12px 15px;
            font-weight: bold;
            color: #4a5568;
            font-size: 12px;
            text-transform: uppercase;
            width: 40%;
            background-color: #f7fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .info-value {
            display: table-cell;
            padding: 12px 15px;
            color: #1a202c;
            font-size: 14px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .score-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background-color: #edf2f7;
            border-radius: 8px;
        }
        
        .score-section h3 {
            color: #2d3748;
            font-size: 16px;
            margin-bottom: 15px;
        }
        
        .score-display {
            font-size: 48px;
            font-weight: bold;
            @if($certificate->cultural_impact_score >= 85)
                color: #38a169;
            @elseif($certificate->cultural_impact_score >= 65)
                color: #3182ce;
            @elseif($certificate->cultural_impact_score >= 40)
                color: #d69e2e;
            @else
                color: #e53e3e;
            @endif
        }
        
        .score-bar {
            width: 100%;
            height: 20px;
            background-color: #cbd5e0;
            border-radius: 10px;
            margin-top: 15px;
            overflow: hidden;
        }
        
        .score-fill {
            height: 100%;
            @if($certificate->cultural_impact_score >= 85)
                background-color: #38a169;
            @elseif($certificate->cultural_impact_score >= 65)
                background-color: #3182ce;
            @elseif($certificate->cultural_impact_score >= 40)
                background-color: #d69e2e;
            @else
                background-color: #e53e3e;
            @endif
            width: {{ $certificate->cultural_impact_score }}%;
        }
        
        .qr-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border: 2px dashed #cbd5e0;
        }
        
        .qr-section img {
            width: 150px;
            height: 150px;
            margin: 10px auto;
        }
        
        .qr-section .url {
            font-size: 10px;
            color: #718096;
            margin-top: 10px;
            word-break: break-all;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            font-size: 11px;
            color: #718096;
        }
        
        .footer .authority {
            font-weight: bold;
            color: #2c5282;
            margin-bottom: 5px;
        }
        
        .seal {
            position: absolute;
            top: 50px;
            right: 50px;
            width: 100px;
            height: 100px;
            border: 3px solid #2c5282;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            color: #2c5282;
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="seal">
            VERIFIED<br>CERTIFICATE
        </div>
        
        <div class="header">
            <h1>CTS™ CERTIFICATE</h1>
            <div class="subtitle">CulturalTranslate Standard Certification</div>
            <div class="standard">Global Authority for Cultural Intelligence and Certified Communication</div>
        </div>
        
        <div class="cts-badge">
            <div class="level">{{ $certificate->cts_level }}</div>
            <div class="description">
                @if($certificate->cts_level === 'CTS-A')
                    Government-Safe Certification
                @elseif($certificate->cts_level === 'CTS-B')
                    Commercial-Safe Certification
                @elseif($certificate->cts_level === 'CTS-C')
                    Reviewed Certification
                @else
                    Risk-Flagged Content
                @endif
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Certificate ID</div>
                    <div class="info-value">{{ $certificate->certificate_id }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Issued Date</div>
                    <div class="info-value">{{ $certificate->issued_at->format('F d, Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Languages</div>
                    <div class="info-value">{{ strtoupper($certificate->source_language) }} → {{ strtoupper($certificate->target_language) }}</div>
                </div>
                @if($certificate->target_country)
                <div class="info-row">
                    <div class="info-label">Target Country</div>
                    <div class="info-value">{{ strtoupper($certificate->target_country) }}</div>
                </div>
                @endif
                @if($certificate->use_case)
                <div class="info-row">
                    <div class="info-label">Use Case</div>
                    <div class="info-value">{{ ucfirst($certificate->use_case) }}</div>
                </div>
                @endif
                @if($certificate->domain)
                <div class="info-row">
                    <div class="info-label">Domain</div>
                    <div class="info-value">{{ ucfirst($certificate->domain) }}</div>
                </div>
                @endif
                @if($certificate->expires_at)
                <div class="info-row">
                    <div class="info-label">Expires</div>
                    <div class="info-value">{{ $certificate->expires_at->format('F d, Y') }}</div>
                </div>
                @endif
            </div>
        </div>
        
        <div class="score-section">
            <h3>Cultural Impact Score™</h3>
            <div class="score-display">{{ $certificate->cultural_impact_score }}/100</div>
            <div class="score-bar">
                <div class="score-fill"></div>
            </div>
        </div>
        
        <div class="qr-section">
            <div style="font-size: 12px; font-weight: bold; color: #2d3748; margin-bottom: 10px;">
                Scan to Verify Online
            </div>
            <img src="{{ $qr_code_url }}" alt="QR Code">
            <div class="url">{{ $verification_url }}</div>
        </div>
        
        <div class="footer">
            <div class="authority">CulturalTranslate Trust Framework</div>
            <div>This certificate verifies process integrity, cultural safety, and verification workflow.</div>
            <div style="margin-top: 10px;">
                Document Hash: {{ substr($certificate->document_hash, 0, 16) }}...
            </div>
            <div style="margin-top: 5px;">
                Generated on {{ now()->format('Y-m-d H:i:s') }} UTC
            </div>
        </div>
    </div>
</body>
</html>
