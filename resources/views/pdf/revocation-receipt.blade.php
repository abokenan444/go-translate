<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Revocation Receipt</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #dc2626;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #dc2626;
            font-size: 24pt;
            margin: 10px 0;
        }
        .header .subtitle {
            color: #666;
            font-size: 10pt;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background: #f3f4f6;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 10px;
            border-left: 4px solid #dc2626;
        }
        .info-grid {
            display: table;
            width: 100%;
            margin: 10px 0;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            padding: 6px 10px;
            width: 35%;
            background: #f9fafb;
        }
        .info-value {
            display: table-cell;
            padding: 6px 10px;
        }
        .warning-box {
            background: #fef2f2;
            border: 2px solid #dc2626;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-box .title {
            color: #dc2626;
            font-weight: bold;
            font-size: 13pt;
            margin-bottom: 10px;
        }
        .qr-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            font-size: 9pt;
            color: #666;
            text-align: center;
        }
        .ledger-hash {
            font-family: 'Courier New', monospace;
            background: #f3f4f6;
            padding: 8px;
            font-size: 9pt;
            word-break: break-all;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>CERTIFICATE REVOCATION RECEIPT</h1>
        <div class="subtitle">
            Official Legal Document<br>
            Cultural Translate Platform
        </div>
    </div>

    <!-- Warning Box -->
    <div class="warning-box">
        <div class="title">⚠ LEGAL NOTICE</div>
        <p>This certificate has been <strong>{{ strtoupper($revocation->action) }}</strong> by government authority and is <strong>NO LONGER VALID</strong> for official or legal purposes.</p>
    </div>

    <!-- Certificate Information -->
    <div class="section">
        <div class="section-title">Certificate Details</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Certificate Number:</div>
                <div class="info-value">{{ $certificate->certificate_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Original Issue Date:</div>
                <div class="info-value">{{ $certificate->issued_at->format('Y-m-d H:i:s T') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Document ID:</div>
                <div class="info-value">#{{ $document->id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Document Type:</div>
                <div class="info-value">{{ $document->document_type }}</div>
            </div>
        </div>
    </div>

    <!-- Revocation Details -->
    <div class="section">
        <div class="section-title">Revocation Details</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Action:</div>
                <div class="info-value"><strong>{{ strtoupper($revocation->action) }}</strong></div>
            </div>
            <div class="info-row">
                <div class="info-label">Effective From:</div>
                <div class="info-value">{{ $revocation->effective_from->format('Y-m-d H:i:s T') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Requested By:</div>
                <div class="info-value">{{ $revocation->requester->name ?? 'N/A' }} ({{ $revocation->requester->email ?? 'N/A' }})</div>
            </div>
            @if($revocation->approved_by)
            <div class="info-row">
                <div class="info-label">Approved By:</div>
                <div class="info-value">{{ $revocation->approver->name ?? 'N/A' }} ({{ $revocation->approver->email ?? 'N/A' }})</div>
            </div>
            <div class="info-row">
                <div class="info-label">Approval Date:</div>
                <div class="info-value">{{ $revocation->approved_at?->format('Y-m-d H:i:s T') ?? 'N/A' }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Legal Basis -->
    <div class="section">
        <div class="section-title">Legal Basis</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Legal Reference:</div>
                <div class="info-value">{{ $revocation->legal_reference }}</div>
            </div>
            @if($revocation->legal_basis_code)
            <div class="info-row">
                <div class="info-label">Legal Basis Code:</div>
                <div class="info-value">{{ $revocation->legal_basis_code }}</div>
            </div>
            @endif
            @if($revocation->jurisdiction_country)
            <div class="info-row">
                <div class="info-label">Jurisdiction:</div>
                <div class="info-value">{{ $revocation->jurisdiction_country }} - {{ $revocation->jurisdiction_purpose ?? 'General' }}</div>
            </div>
            @endif
        </div>
        <div style="margin-top: 15px;">
            <strong>Reason for {{ ucfirst($revocation->action) }}:</strong>
            <p style="margin: 10px 0; padding: 10px; background: #f9fafb; border-left: 3px solid #dc2626;">
                {{ $revocation->reason }}
            </p>
        </div>
    </div>

    <!-- Blockchain Verification -->
    <div class="section">
        <div class="section-title">Tamper-Evident Verification</div>
        <p><strong>Decision Ledger Hash:</strong></p>
        <div class="ledger-hash">{{ $ledgerHash }}</div>
        <p style="font-size: 9pt; color: #666; margin-top: 5px;">
            This hash is part of an immutable decision ledger. Any tampering with this record can be detected through chain verification.
        </p>
    </div>

    <!-- QR Code Verification -->
    <div class="qr-section">
        <p><strong>Verify Online:</strong></p>
        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" style="width: 150px; height: 150px; margin: 10px 0;">
        <p style="font-size: 9pt;">{{ $verifyUrl }}</p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>Cultural Translate Platform</strong></p>
        <p>This receipt was generated on {{ $generatedAt->format('Y-m-d H:i:s T') }}</p>
        <p>Receipt ID: {{ $revocation->id }} | Certificate: {{ $certificate->certificate_number }}</p>
        <p style="margin-top: 10px; font-size: 8pt;">
            This is an official legal document. Unauthorized reproduction or alteration is prohibited.<br>
            For verification inquiries, contact: support@culturaltranslate.com
        </p>
    </div>
</body>
</html>
