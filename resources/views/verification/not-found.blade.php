<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Not Found - CulturalTranslate</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f5f7fa; color: #2d3748; line-height: 1.6; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .container { max-width: 600px; padding: 40px; text-align: center; }
        .card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 60px 40px; }
        .icon { font-size: 64px; margin-bottom: 20px; }
        h1 { font-size: 28px; color: #1a202c; margin-bottom: 15px; }
        p { color: #718096; margin-bottom: 10px; }
        .code { background: #f7fafc; padding: 10px 15px; border-radius: 6px; font-family: monospace; color: #2d3748; margin: 20px 0; display: inline-block; }
        .btn { display: inline-block; margin-top: 30px; padding: 12px 24px; background: #4299e1; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; }
        .btn:hover { background: #3182ce; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="icon">⚠️</div>
            <h1>Certificate Not Found</h1>
            <p>The verification code you entered could not be found in our system.</p>
            <div class="code">{{ $code }}</div>
            <p style="margin-top: 20px;">Please check the code and try again, or contact the issuer for assistance.</p>
            <a href="https://culturaltranslate.com" class="btn">Go to Homepage</a>
        </div>
    </div>
</body>
</html>
