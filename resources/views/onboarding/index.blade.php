<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Cultural Translate</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .onboarding-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 900px;
            width: 100%;
            overflow: hidden;
        }

        .progress-bar {
            height: 6px;
            background: #f0f0f0;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transition: width 0.5s ease;
        }

        .onboarding-content {
            padding: 60px;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .step.active .step-number {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: scale(1.2);
        }

        .step.completed .step-number {
            background: #4caf50;
            color: white;
        }

        .step.completed .step-number::after {
            content: '✓';
        }

        .step-label {
            font-size: 12px;
            color: #666;
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .step-icon {
            font-size: 60px;
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 32px;
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }

        .step-description {
            font-size: 18px;
            color: #666;
            text-align: center;
            margin-bottom: 40px;
        }

        .feature-list {
            list-style: none;
            margin: 30px 0;
        }

        .feature-list li {
            padding: 15px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 10px;
            display: flex;
            align-items: center;
        }

        .feature-list li::before {
            content: '✓';
            width: 30px;
            height: 30px;
            background: #4caf50;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .integration-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .integration-card {
            padding: 25px;
            background: #f8f9fa;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .integration-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }

        .integration-card.selected {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .integration-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .next-steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .next-step-card {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }

        .next-step-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .next-step-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .next-step-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .btn {
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: #666;
            border: 2px solid #e0e0e0;
        }

        .btn-secondary:hover {
            background: #f8f9fa;
        }

        .confetti {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
        }
    </style>
</head>
<body>
    <div class="onboarding-container">
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill" style="width: 20%"></div>
        </div>

        <div class="onboarding-content">
            <!-- Step Indicator -->
            <div class="step-indicator">
                @foreach($steps as $step)
                <div class="step {{ $currentStep == $step['number'] ? 'active' : '' }}" data-step="{{ $step['number'] }}">
                    <div class="step-number">{{ $step['number'] }}</div>
                    <div class="step-label">{{ $step['title'] }}</div>
                </div>
                @endforeach
            </div>

            <!-- Step Content -->
            @foreach($steps as $step)
            <div class="step-content {{ $currentStep == $step['number'] ? 'active' : '' }}" data-step="{{ $step['number'] }}">
                <div class="step-icon">{{ $step['icon'] }}</div>
                <h1>{{ $step['content']['heading'] }}</h1>
                <p class="step-description">{{ $step['description'] }}</p>

                @if($step['number'] == 1)
                    <ul class="feature-list">
                        @foreach($step['content']['features'] as $feature)
                        <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                @endif

                @if($step['number'] == 2)
                    <form id="companyForm">
                        @foreach($step['content']['fields'] as $field)
                        <div class="form-group">
                            <label>{{ $field['label'] }}</label>
                            <input type="{{ $field['type'] }}" name="{{ $field['name'] }}" required>
                        </div>
                        @endforeach
                    </form>
                @endif

                @if($step['number'] == 3)
                    <div id="translationDemo">
                        <p><strong>Sample Text:</strong></p>
                        <p>{{ $step['content']['sample_text'] }}</p>
                        <div style="margin: 20px 0;">
                            <button class="btn btn-primary" onclick="runDemo()">Try Translation →</button>
                        </div>
                        <div id="demoResults"></div>
                    </div>
                @endif

                @if($step['number'] == 4)
                    <div class="integration-grid">
                        @foreach($step['content']['integrations'] as $integration)
                        <div class="integration-card" data-integration="{{ $integration['id'] }}">
                            <div class="integration-icon">{{ $integration['icon'] }}</div>
                            <div><strong>{{ $integration['name'] }}</strong></div>
                            <div style="font-size: 14px; color: #666; margin-top: 5px;">{{ $integration['description'] }}</div>
                        </div>
                        @endforeach
                    </div>
                @endif

                @if($step['number'] == 5)
                    <div class="next-steps-grid">
                        @foreach($step['content']['next_steps'] as $nextStep)
                        <a href="{{ $nextStep['url'] }}" class="next-step-card">
                            <div class="next-step-icon">{{ $nextStep['icon'] }}</div>
                            <div class="next-step-title">{{ $nextStep['title'] }}</div>
                            <div style="font-size: 14px; color: #666;">{{ $nextStep['description'] }}</div>
                        </a>
                        @endforeach
                    </div>
                @endif
            </div>
            @endforeach

            <!-- Navigation Buttons -->
            <div class="button-group">
                <button class="btn btn-secondary" id="skipBtn" onclick="skipOnboarding()">Skip</button>
                <div>
                    <button class="btn btn-secondary" id="prevBtn" onclick="previousStep()" style="display: none; margin-right: 10px;">← Previous</button>
                    <button class="btn btn-primary" id="nextBtn" onclick="nextStep()">Next →</button>
                </div>
            </div>
        </div>
    </div>

    <canvas class="confetti" id="confetti"></canvas>

    <script>
        let currentStep = {{ $currentStep }};
        const totalSteps = {{ count($steps) }};

        function updateUI() {
            // Update progress bar
            const progress = (currentStep / totalSteps) * 100;
            document.getElementById('progressFill').style.width = progress + '%';

            // Update step indicator
            document.querySelectorAll('.step').forEach((step, index) => {
                step.classList.remove('active', 'completed');
                if (index + 1 < currentStep) {
                    step.classList.add('completed');
                } else if (index + 1 === currentStep) {
                    step.classList.add('active');
                }
            });

            // Update content
            document.querySelectorAll('.step-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelector(`.step-content[data-step="${currentStep}"]`).classList.add('active');

            // Update buttons
            document.getElementById('prevBtn').style.display = currentStep > 1 ? 'inline-block' : 'none';
            document.getElementById('nextBtn').textContent = currentStep === totalSteps ? 'Get Started →' : 'Next →';
            document.getElementById('skipBtn').style.display = currentStep === totalSteps ? 'none' : 'inline-block';
        }

        async function nextStep() {
            if (currentStep < totalSteps) {
                await completeStep(currentStep);
                currentStep++;
                updateUI();
            } else {
                launchConfetti();
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 2000);
            }
        }

        function previousStep() {
            if (currentStep > 1) {
                currentStep--;
                updateUI();
            }
        }

        async function completeStep(step) {
            try {
                const response = await fetch('/onboarding/complete-step', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        step: step,
                        data: getStepData(step)
                    })
                });

                const result = await response.json();
                return result.success;
            } catch (error) {
                console.error('Failed to complete step:', error);
                return false;
            }
        }

        function getStepData(step) {
            if (step === 2) {
                const form = document.getElementById('companyForm');
                const formData = new FormData(form);
                return Object.fromEntries(formData);
            }
            return {};
        }

        function skipOnboarding() {
            if (confirm('Are you sure you want to skip onboarding?')) {
                window.location.href = '/onboarding/skip';
            }
        }

        function launchConfetti() {
            const canvas = document.getElementById('confetti');
            const ctx = canvas.getContext('2d');
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;

            const pieces = [];
            const numberOfPieces = 200;
            const colors = ['#667eea', '#764ba2', '#4caf50', '#ffd700', '#ff6b6b'];

            for (let i = 0; i < numberOfPieces; i++) {
                pieces.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height - canvas.height,
                    r: Math.random() * 6 + 2,
                    d: Math.random() * 10 + 10,
                    color: colors[Math.floor(Math.random() * colors.length)],
                    tilt: Math.random() * 10 - 10
                });
            }

            function draw() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                pieces.forEach((p, i) => {
                    ctx.beginPath();
                    ctx.lineWidth = p.r / 2;
                    ctx.strokeStyle = p.color;
                    ctx.moveTo(p.x + p.tilt + p.r, p.y);
                    ctx.lineTo(p.x + p.tilt, p.y + p.tilt + p.r);
                    ctx.stroke();

                    p.tilt = Math.sin(p.d) * 15;
                    p.y += (Math.cos(p.d) + 3 + p.r / 2) / 2;
                    p.x += Math.sin(p.d);
                    p.d += 0.05;

                    if (p.y > canvas.height) {
                        pieces[i] = {
                            x: Math.random() * canvas.width,
                            y: -20,
                            r: p.r,
                            d: p.d,
                            color: p.color,
                            tilt: p.tilt
                        };
                    }
                });

                requestAnimationFrame(draw);
            }

            draw();
        }

        updateUI();
    </script>
</body>
</html>
