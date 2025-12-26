<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Compliance Report - {{ $start_date->format('Y-m-d') }} to {{ $end_date->format('Y-m-d') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #1e40af;
            font-size: 20px;
            margin: 0 0 5px 0;
        }
        .header .subtitle {
            color: #64748b;
            font-size: 11px;
        }
        .metrics-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .metric-row {
            display: table-row;
        }
        .metric-cell {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        .metric-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .metric-value {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }
        .metric-value.success {
            color: #16a34a;
        }
        .metric-value.warning {
            color: #ea580c;
        }
        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #1e40af;
            border-bottom: 2px solid #bfdbfe;
            padding-bottom: 3px;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        th {
            background: #dbeafe;
            color: #1e40af;
            padding: 5px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #93c5fd;
        }
        td {
            padding: 4px 5px;
            border: 1px solid #e2e8f0;
        }
        tr:nth-child(even) {
            background: #f8fafc;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-valid { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef3c7; color: #854d0e; }
        .status-frozen { background: #fed7aa; color: #9a3412; }
        .status-revoked { background: #fecaca; color: #991b1b; }
        .chart-container {
            margin: 10px 0;
            padding: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .bar-chart {
            display: table;
            width: 100%;
        }
        .bar-row {
            display: table-row;
            margin-bottom: 3px;
        }
        .bar-label {
            display: table-cell;
            width: 30%;
            padding: 3px 5px;
            font-size: 9px;
        }
        .bar-container {
            display: table-cell;
            width: 60%;
            padding: 3px 0;
        }
        .bar-fill {
            height: 12px;
            background: linear-gradient(to right, #3b82f6, #2563eb);
            border-radius: 2px;
        }
        .bar-value {
            display: table-cell;
            width: 10%;
            padding: 3px 5px;
            text-align: right;
            font-size: 9px;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 8px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏛️ Government Compliance Report</h1>
        <div class="subtitle">
            Period: {{ $start_date->format('F j, Y') }} - {{ $end_date->format('F j, Y') }}<br>
            Generated: {{ $report_generated_at->format('F j, Y H:i:s') }}
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="metrics-grid">
        <div class="metric-row">
            <div class="metric-cell">
                <div class="metric-label">Total Documents</div>
                <div class="metric-value">{{ number_format($total_documents) }}</div>
            </div>
            <div class="metric-cell">
                <div class="metric-label">Certificates Issued</div>
                <div class="metric-value success">{{ number_format($certificates_issued) }}</div>
            </div>
            <div class="metric-cell">
                <div class="metric-label">Compliance Rate</div>
                <div class="metric-value {{ $compliance_rate >= 90 ? 'success' : 'warning' }}">{{ $compliance_rate }}%</div>
            </div>
        </div>
        <div class="metric-row">
            <div class="metric-cell">
                <div class="metric-label">Avg Processing Time</div>
                <div class="metric-value">{{ $avg_processing_hours }}h</div>
            </div>
            <div class="metric-cell">
                <div class="metric-label">Total Disputes</div>
                <div class="metric-value {{ $total_disputes == 0 ? 'success' : 'warning' }}">{{ number_format($total_disputes) }}</div>
            </div>
            <div class="metric-cell">
                <div class="metric-label">Dispute Rate</div>
                <div class="metric-value {{ $dispute_rate < 5 ? 'success' : 'warning' }}">{{ $dispute_rate }}%</div>
            </div>
        </div>
    </div>

    <!-- Documents by Status -->
    <div class="section">
        <div class="section-title">📊 Documents by Status</div>
        <div class="chart-container">
            <div class="bar-chart">
                @php
                    $maxCount = max(array_values($documents_by_status));
                @endphp
                @foreach($documents_by_status as $status => $count)
                <div class="bar-row">
                    <div class="bar-label">{{ ucfirst($status) }}</div>
                    <div class="bar-container">
                        <div class="bar-fill" style="width: {{ $maxCount > 0 ? ($count / $maxCount * 100) : 0 }}%;"></div>
                    </div>
                    <div class="bar-value">{{ $count }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Documents by Type -->
    @if($documents_by_type->count() > 0)
    <div class="section">
        <div class="section-title">📄 Top Document Types</div>
        <table>
            <thead>
                <tr>
                    <th>Document Type</th>
                    <th style="text-align: right;">Count</th>
                    <th style="text-align: right;">Percentage</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents_by_type as $item)
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $item->document_type)) }}</td>
                    <td style="text-align: right;">{{ $item->count }}</td>
                    <td style="text-align: right;">{{ $total_documents > 0 ? round(($item->count / $total_documents) * 100, 1) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Ledger Events -->
    @if(!empty($ledger_events))
    <div class="section">
        <div class="section-title">🔒 Decision Ledger Activity</div>
        <table>
            <thead>
                <tr>
                    <th>Event Type</th>
                    <th style="text-align: right;">Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ledger_events as $eventType => $count)
                <tr>
                    <td>{{ ucwords(str_replace('_', ' ', $eventType)) }}</td>
                    <td style="text-align: right;">{{ $count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Recent Certificates -->
    <div class="section">
        <div class="section-title">📜 Recent Certificates (Last 50)</div>
        <table>
            <thead>
                <tr>
                    <th>Cert ID</th>
                    <th>Document ID</th>
                    <th>Status</th>
                    <th>Legal Status</th>
                    <th>Issued At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recent_certificates as $cert)
                <tr>
                    <td>{{ $cert->id }}</td>
                    <td>{{ $cert->document_id }}</td>
                    <td>
                        <span class="status-badge status-{{ $cert->status }}">{{ $cert->status }}</span>
                    </td>
                    <td>
                        @php
                            $legalStatus = $cert->legal_status ?? 'valid';
                        @endphp
                        <span class="status-badge status-{{ $legalStatus }}">{{ $legalStatus }}</span>
                    </td>
                    <td>{{ $cert->issued_at?->format('Y-m-d H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <strong>Cultural Translate Platform</strong> - Government Compliance Report<br>
        This document is confidential and intended for authorized government personnel only.<br>
        Report ID: COMP-{{ $entity_id }}-{{ $report_generated_at->format('YmdHis') }}
    </div>
</body>
</html>
