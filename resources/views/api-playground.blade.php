<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Playground - Cultural Translate</title>
    <link rel="stylesheet" href="/css/dark-mode.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        header {
            padding: 40px 0;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 40px;
        }
        
        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: var(--brand-primary);
        }
        
        .subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
        }
        
        .playground {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .panel {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px var(--shadow-color);
        }
        
        .panel h2 {
            margin-bottom: 20px;
            color: var(--brand-primary);
            font-size: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-primary);
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--input-border);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
        }
        
        textarea {
            resize: vertical;
            min-height: 120px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        }
        
        button {
            background: var(--button-bg);
            color: var(--button-text);
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px var(--shadow-color);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .response-container {
            margin-top: 20px;
        }
        
        pre {
            background: var(--code-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 20px;
            overflow-x: auto;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 13px;
            line-height: 1.5;
        }
        
        .endpoint-list {
            list-style: none;
        }
        
        .endpoint-item {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .endpoint-item:hover {
            transform: translateX(5px);
            border-color: var(--brand-primary);
        }
        
        .endpoint-method {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 12px;
            margin-right: 10px;
        }
        
        .method-get {
            background: var(--success);
            color: white;
        }
        
        .method-post {
            background: var(--brand-primary);
            color: white;
        }
        
        .loading {
            display: inline-block;
            margin-left: 10px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .stat-card {
            background: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--brand-primary);
        }
        
        .stat-label {
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🌍 API Playground</h1>
            <p class="subtitle">Test Cultural Translate API endpoints with Redis caching</p>
        </header>
        
        <div class="playground">
            <!-- Request Panel -->
            <div class="panel">
                <h2>📤 Request</h2>
                
                <div class="form-group">
                    <label>API Key</label>
                    <input type="text" id="apiKey" value="d232f267-0044-4bec-976b-502745745ffe" placeholder="Your API key">
                </div>
                
                <div class="form-group">
                    <label>Endpoint</label>
                    <select id="endpoint">
                        <option value="/api/sandbox/v1/translate">POST /translate - Translate text</option>
                        <option value="/api/sandbox/v1/languages">GET /languages - Get languages</option>
                        <option value="/api/sandbox/v1/cache/stats">GET /cache/stats - Cache statistics</option>
                        <option value="/api/sandbox/v1/usage">GET /usage - API usage</option>
                        <option value="/api/sandbox/v1/rate-limit/test">GET /rate-limit/test - Rate limits</option>
                    </select>
                </div>
                
                <div class="form-group" id="requestBodyGroup">
                    <label>Request Body (JSON)</label>
                    <textarea id="requestBody">{
  "text": "Hello World",
  "target_language": "ar",
  "tone": "formal"
}</textarea>
                </div>
                
                <button onclick="sendRequest()">Send Request</button>
                
                <!-- Quick Tests -->
                <h3 style="margin-top: 30px; margin-bottom: 15px;">⚡ Quick Tests</h3>
                <ul class="endpoint-list">
                    <li class="endpoint-item" onclick="testTranslation()">
                        <span class="endpoint-method method-post">POST</span>
                        <span>Test Translation + Cache</span>
                    </li>
                    <li class="endpoint-item" onclick="testCacheStats()">
                        <span class="endpoint-method method-get">GET</span>
                        <span>View Cache Statistics</span>
                    </li>
                    <li class="endpoint-item" onclick="testLanguages()">
                        <span class="endpoint-method method-get">GET</span>
                        <span>List Languages</span>
                    </li>
                </ul>
            </div>
            
            <!-- Response Panel -->
            <div class="panel">
                <h2>📥 Response</h2>
                <div class="response-container">
                    <pre id="response">No request sent yet. Select an endpoint and click "Send Request".</pre>
                </div>
                
                <div id="cacheStatsDisplay" style="display: none;">
                    <h3 style="margin-top: 30px; margin-bottom: 15px;">📊 Cache Performance</h3>
                    <div class="stats-grid" id="statsGrid"></div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="/js/dark-mode.js"></script>
    <script>
        const baseUrl = 'https://culturaltranslate.com';
        
        // Toggle request body visibility based on endpoint
        document.getElementById('endpoint').addEventListener('change', function() {
            const method = this.value.includes('/translate') ? 'POST' : 'GET';
            document.getElementById('requestBodyGroup').style.display = 
                method === 'POST' ? 'block' : 'none';
        });
        
        async function sendRequest() {
            const apiKey = document.getElementById('apiKey').value;
            const endpoint = document.getElementById('endpoint').value;
            const body = document.getElementById('requestBody').value;
            const responseEl = document.getElementById('response');
            
            // Show loading
            responseEl.textContent = 'Loading...';
            
            try {
                const isPost = endpoint.includes('/translate');
                const options = {
                    method: isPost ? 'POST' : 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${apiKey}`,
                        'Content-Type': 'application/json'
                    }
                };
                
                if (isPost) {
                    options.body = body;
                }
                
                const response = await fetch(baseUrl + endpoint, options);
                const data = await response.json();
                
                responseEl.textContent = JSON.stringify(data, null, 2);
                
                // Show cache stats if available
                if (data.meta && data.meta.cached !== undefined) {
                    showCacheIndicator(data.meta);
                }
                
                if (endpoint.includes('cache/stats')) {
                    displayCacheStats(data.data.stats);
                }
                
            } catch (error) {
                responseEl.textContent = `Error: ${error.message}`;
            }
        }
        
        function showCacheIndicator(meta) {
            const indicator = meta.cached 
                ? `✅ Cached (${meta.processing_time_ms}ms)` 
                : `⏳ Uncached (${meta.processing_time_ms}ms)`;
            console.log(indicator);
        }
        
        function displayCacheStats(stats) {
            const statsGrid = document.getElementById('statsGrid');
            const display = document.getElementById('cacheStatsDisplay');
            
            statsGrid.innerHTML = `
                <div class="stat-card">
                    <div class="stat-value">${stats.total_keys}</div>
                    <div class="stat-label">Total Keys</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${stats.hit_rate}%</div>
                    <div class="stat-label">Hit Rate</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${stats.hits}</div>
                    <div class="stat-label">Cache Hits</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${stats.misses}</div>
                    <div class="stat-label">Cache Misses</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">${stats.memory_used}</div>
                    <div class="stat-label">Memory Used</div>
                </div>
            `;
            
            display.style.display = 'block';
        }
        
        // Quick test functions
        function testTranslation() {
            document.getElementById('endpoint').value = '/api/sandbox/v1/translate';
            document.getElementById('requestBodyGroup').style.display = 'block';
            sendRequest();
        }
        
        function testCacheStats() {
            document.getElementById('endpoint').value = '/api/sandbox/v1/cache/stats';
            document.getElementById('requestBodyGroup').style.display = 'none';
            sendRequest();
        }
        
        function testLanguages() {
            document.getElementById('endpoint').value = '/api/sandbox/v1/languages';
            document.getElementById('requestBodyGroup').style.display = 'none';
            sendRequest();
        }
    </script>
</body>
</html>
