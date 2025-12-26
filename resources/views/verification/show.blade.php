<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Verification - CulturalTranslate</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f7fa; color: #2d3748; line-height: 1.6; }
        .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .header { text-align: center; margin-bottom: 40px; }
        .header h1 { font-size: 32px; color: #1a202c; margin-bottom: 10px; }
        .header p { color: #718096; }
        .card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 30px; margin-bottom: 20px; }
        .status { display: inline-block; padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 14px; }
        .status.valid { background: #c6f6d5; color: #22543d; }
        .status.revoked { background: #fed7d7; color: #742a2a; }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0; }
        .info-item label { display: block; font-size: 12px; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
        .info-item value { display: block; font-size: 16px; color: #2d3748; font-weight: 500; }
        .seals { display: flex; flex-wrap: wrap; gap: 10px; margin: 20px 0; }
        .seal { padding: 8px 16px; border-radius: 6px; color: white; font-size: 13px; font-weight: 600; }
        .acceptance-section { margin-top: 30px; }
        .acceptance-section h3 { font-size: 20px; margin-bottom: 15px; color: #1a202c; }
        .acceptance-content { background: #f7fafc; padding: 20px; border-radius: 8px; border-left: 4px solid #4299e1; white-space: pre-line; }
        .country-selector { margin: 20px 0; }
        .country-selector select { padding: 10px; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 14px; width: 100%; max-width: 300px; cursor: pointer; }
        .footer { text-align: center; margin-top: 40px; padding: 20px; color: #718096; font-size: 14px; }
        .qr-code { text-align: center; margin: 20px 0; }
        .qr-code img { max-width: 200px; border: 2px solid #e2e8f0; border-radius: 8px; padding: 10px; background: white; }
        .action-buttons { display: flex; gap: 10px; margin-top: 20px; flex-wrap: wrap; }
        .btn { padding: 12px 24px; border: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: all 0.3s; }
        .btn-primary { background: #4299e1; color: white; }
        .btn-primary:hover { background: #3182ce; }
        .btn-secondary { background: #718096; color: white; }
        .btn-secondary:hover { background: #4a5568; }
        .dynamic-rules { display: none; margin-top: 20px; animation: fadeIn 0.3s; }
        .dynamic-rules.show { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .loading { display: inline-block; width: 16px; height: 16px; border: 2px solid #cbd5e0; border-top-color: #4299e1; border-radius: 50%; animation: spin 0.6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✓ Certificate Verified</h1>
            <p>This certificate has been successfully verified</p>
        </div>

        <div class="card">
            <h2 style="margin-bottom: 20px;">Certificate Details</h2>
            
            <div style="margin-bottom: 20px;">
                <span class="status {{ $certificate['status'] }}">
                    {{ ucfirst($certificate['status']) }}
                </span>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <label>Certificate Number</label>
                    <value>{{ $certificate['certificate_number'] }}</value>
                </div>
                <div class="info-item">
                    <label>Issued Date</label>
                    <value>{{ $certificate['issued_at'] }}</value>
                </div>
                <div class="info-item">
                    <label>Translator</label>
                    <value>{{ $certificate['translator_name'] ?? 'N/A' }}</value>
                </div>
                <div class="info-item">
                    <label>Verified</label>
                    <value>{{ $certificate['verification_count'] }} times</value>
                </div>
            </div>

            @if(count($seals) > 0)
            <div style="margin-top: 30px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600;">Compliance Seals</label>
                <div class="seals">
                    @foreach($seals as $seal)
                    <span class="seal" style="background-color: {{ $seal['color'] }};" title="{{ $seal['description'] }}">
                        {{ $seal['name'] }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- QR Code -->
            <div class="qr-code">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode(url('/verify/' . $certificate['verification_code'])) }}" alt="QR Code">
                <p style="margin-top: 10px; font-size: 12px; color: #718096;">Scan to verify</p>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ url('/verify/' . $certificate['verification_code'] . '/pdf') }}" class="btn btn-primary">
                    📄 Download Certificate (PDF)
                </a>
                <button onclick="window.print()" class="btn btn-secondary">
                    🖨️ Print
                </button>
            </div>
        </div>

        <div class="card acceptance-section">
            <h3>{{ $acceptanceRules['global']['title'] }}</h3>
            <div class="acceptance-content">{{ $acceptanceRules['global']['content'] }}</div>

            <div class="country-selector">
                <label style="display: block; margin-bottom: 10px; font-weight: 600;">Show acceptance rules for:</label>
                <select id="countrySelector">
                    <option value="">Select your country</option>
                    @foreach($countries as $code => $name)
                    <option value="{{ $code }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="dynamicRules" class="dynamic-rules"></div>
        </div>

        <div class="footer">
            <p>Powered by <strong>CulturalTranslate</strong> Trust Framework</p>
            <p style="margin-top: 10px; font-size: 12px;">This verification was logged and is part of an immutable audit trail.</p>
        </div>
    </div>

    <script>
        const countrySelector = document.getElementById('countrySelector');
        const dynamicRules = document.getElementById('dynamicRules');
        const verificationCode = '{{ $certificate['verification_code'] }}';

        countrySelector.addEventListener('change', async function() {
            const country = this.value;
            
            if (!country) {
                dynamicRules.classList.remove('show');
                dynamicRules.innerHTML = '';
                return;
            }

            // Show loading
            dynamicRules.innerHTML = '<div style="text-align: center; padding: 20px;"><span class="loading"></span> Loading...</div>';
            dynamicRules.classList.add('show');

            try {
                const response = await fetch(`/api/verify/${verificationCode}?country=${country}`);
                const data = await response.json();

                if (data.success && data.acceptance_rules) {
                    let html = '';

                    if (data.acceptance_rules.country) {
                        html += `
                            <div style="margin-top: 20px;">
                                <h3>${data.acceptance_rules.country.title}</h3>
                                <div class="acceptance-content">${data.acceptance_rules.country.content}</div>
                            </div>
                        `;
                    }

                    if (data.acceptance_rules.sectors && data.acceptance_rules.sectors.length > 0) {
                        html += '<div style="margin-top: 20px;"><h3>Sector-Specific Rules</h3>';
                        data.acceptance_rules.sectors.forEach(sector => {
                            html += `
                                <div style="margin-top: 15px;">
                                    <h4 style="font-size: 16px; color: #4a5568; margin-bottom: 10px;">${sector.title}</h4>
                                    <div class="acceptance-content">${sector.content}</div>
                                </div>
                            `;
                        });
                        html += '</div>';
                    }

                    dynamicRules.innerHTML = html;
                } else {
                    dynamicRules.innerHTML = '<div class="acceptance-content">No specific rules found for this country.</div>';
                }
            } catch (error) {
                console.error('Error fetching acceptance rules:', error);
                dynamicRules.innerHTML = '<div class="acceptance-content" style="border-left-color: #f56565;">Error loading acceptance rules. Please try again.</div>';
            }
        });
    </script>
</body>
</html>
