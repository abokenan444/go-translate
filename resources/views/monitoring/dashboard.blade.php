<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Platform Health Monitor - CulturalTranslate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .pulse-animation {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        .status-ok { color: #10b981; }
        .status-warning { color: #f59e0b; }
        .status-error { color: #ef4444; }
        .bg-status-ok { background-color: #10b981; }
        .bg-status-warning { background-color: #f59e0b; }
        .bg-status-error { background-color: #ef4444; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-heartbeat text-3xl text-blue-600"></i>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Platform Health Monitor</h1>
                            <p class="text-sm text-gray-500">Real-time system monitoring dashboard</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Last Check</div>
                            <div id="last-check-time" class="text-sm font-semibold text-gray-900">--</div>
                        </div>
                        <button onclick="runManualCheck()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition">
                            <i class="fas fa-sync-alt"></i>
                            <span>Run Check</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Overall Status Card -->
            <div id="overall-status-card" class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div id="overall-status-icon" class="text-6xl pulse-animation">
                            <i class="fas fa-spinner fa-spin text-gray-400"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold" id="overall-status-text">Loading...</h2>
                            <p class="text-gray-500">Overall Platform Status</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div id="health-percentage" class="text-5xl font-bold text-gray-900">--%</div>
                        <div class="text-sm text-gray-500">Health Score</div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">System Health</p>
                            <p id="stat-health" class="text-2xl font-bold text-gray-900">--</p>
                        </div>
                        <i class="fas fa-server text-3xl text-blue-500"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Routes</p>
                            <p id="stat-routes" class="text-2xl font-bold text-gray-900">--</p>
                        </div>
                        <i class="fas fa-route text-3xl text-green-500"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Services</p>
                            <p id="stat-services" class="text-2xl font-bold text-gray-900">--</p>
                        </div>
                        <i class="fas fa-cogs text-3xl text-purple-500"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Errors</p>
                            <p id="stat-errors" class="text-2xl font-bold text-gray-900">--</p>
                        </div>
                        <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white rounded-xl shadow-lg mb-8">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button onclick="switchTab('health')" class="tab-button border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
                            <i class="fas fa-heartbeat mr-2"></i>System Health
                        </button>
                        <button onclick="switchTab('routes')" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-route mr-2"></i>Routes
                        </button>
                        <button onclick="switchTab('services')" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-cogs mr-2"></i>Services
                        </button>
                        <button onclick="switchTab('reports')" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            <i class="fas fa-file-alt mr-2"></i>Reports
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Health Tab -->
                    <div id="tab-health" class="tab-content">
                        <h3 class="text-lg font-semibold mb-4">System Health Checks</h3>
                        <div id="health-checks" class="space-y-3">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Routes Tab -->
                    <div id="tab-routes" class="tab-content hidden">
                        <h3 class="text-lg font-semibold mb-4">Route Validation</h3>
                        <div id="route-checks" class="space-y-3">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Services Tab -->
                    <div id="tab-services" class="tab-content hidden">
                        <h3 class="text-lg font-semibold mb-4">Service Integrity</h3>
                        <div id="service-checks" class="space-y-3">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Reports Tab -->
                    <div id="tab-reports" class="tab-content hidden">
                        <h3 class="text-lg font-semibold mb-4">Saved Reports</h3>
                        <div id="reports-list" class="space-y-3">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let currentTab = 'health';
        let latestData = null;

        // Auto-refresh every 30 seconds
        setInterval(loadStatus, 30000);

        // Load on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadStatus();
            loadReports();
        });

        function switchTab(tab) {
            currentTab = tab;
            
            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            event.target.closest('.tab-button').classList.remove('border-transparent', 'text-gray-500');
            event.target.closest('.tab-button').classList.add('border-blue-500', 'text-blue-600');

            // Update tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById('tab-' + tab).classList.remove('hidden');
        }

        function loadStatus() {
            fetch('/api/monitoring/status?type=full', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                latestData = data;
                updateDashboard(data);
            })
            .catch(error => {
                console.error('Error loading status:', error);
            });
        }

        function updateDashboard(data) {
            // Update timestamp
            document.getElementById('last-check-time').textContent = new Date(data.timestamp).toLocaleTimeString();

            // Update overall status
            const statusIcon = document.getElementById('overall-status-icon');
            const statusText = document.getElementById('overall-status-text');
            
            statusIcon.innerHTML = getStatusIcon(data.overall_status, true);
            statusText.textContent = data.overall_status;
            statusText.className = 'text-3xl font-bold status-' + data.overall_status.toLowerCase();

            // Update health percentage
            if (data.data.health && data.data.health.summary) {
                document.getElementById('health-percentage').textContent = data.data.health.summary.health_percentage + '%';
            }

            // Update stats
            if (data.data.health) {
                document.getElementById('stat-health').textContent = data.data.health.status;
                document.getElementById('stat-health').className = 'text-2xl font-bold status-' + data.data.health.status.toLowerCase();
            }

            if (data.data.routes) {
                const totalRoutes = data.data.routes.results.route_definitions?.total_routes || 0;
                document.getElementById('stat-routes').textContent = totalRoutes;
            }

            if (data.data.services && data.data.services.summary) {
                document.getElementById('stat-services').textContent = 
                    data.data.services.summary.operational + '/' + data.data.services.summary.total_services;
            }

            // Count total errors
            let totalErrors = 0;
            if (data.data.health) totalErrors += data.data.health.errors.length;
            if (data.data.routes) totalErrors += data.data.routes.errors.length;
            if (data.data.services) totalErrors += data.data.services.errors.length;
            document.getElementById('stat-errors').textContent = totalErrors;
            document.getElementById('stat-errors').className = totalErrors > 0 ? 'text-2xl font-bold text-red-600' : 'text-2xl font-bold text-green-600';

            // Update health checks
            if (data.data.health) {
                updateHealthChecks(data.data.health);
            }

            // Update route checks
            if (data.data.routes) {
                updateRouteChecks(data.data.routes);
            }

            // Update service checks
            if (data.data.services) {
                updateServiceChecks(data.data.services);
            }
        }

        function updateHealthChecks(health) {
            const container = document.getElementById('health-checks');
            container.innerHTML = '';

            for (const [key, result] of Object.entries(health.results)) {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between p-4 bg-gray-50 rounded-lg';
                div.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">${getStatusIcon(result.status)}</span>
                        <div>
                            <div class="font-semibold text-gray-900">${formatKey(key)}</div>
                            <div class="text-sm text-gray-500">${result.message}</div>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-status-${result.status.toLowerCase()} text-white">
                        ${result.status}
                    </span>
                `;
                container.appendChild(div);
            }
        }

        function updateRouteChecks(routes) {
            const container = document.getElementById('route-checks');
            container.innerHTML = '';

            for (const [key, result] of Object.entries(routes.results)) {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between p-4 bg-gray-50 rounded-lg';
                div.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">${getStatusIcon(result.status)}</span>
                        <div>
                            <div class="font-semibold text-gray-900">${formatKey(key)}</div>
                            <div class="text-sm text-gray-500">${result.message}</div>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-status-${result.status.toLowerCase()} text-white">
                        ${result.status}
                    </span>
                `;
                container.appendChild(div);
            }
        }

        function updateServiceChecks(services) {
            const container = document.getElementById('service-checks');
            container.innerHTML = '';

            for (const [key, result] of Object.entries(services.results)) {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between p-4 bg-gray-50 rounded-lg';
                div.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">${getStatusIcon(result.status)}</span>
                        <div>
                            <div class="font-semibold text-gray-900">${formatKey(key)}</div>
                            <div class="text-sm text-gray-500">${result.message}</div>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-status-${result.status.toLowerCase()} text-white">
                        ${result.status}
                    </span>
                `;
                container.appendChild(div);
            }
        }

        function loadReports() {
            fetch('/api/monitoring/reports', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(reports => {
                const container = document.getElementById('reports-list');
                container.innerHTML = '';

                if (reports.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-center py-8">No reports available</p>';
                    return;
                }

                reports.slice(0, 10).forEach(report => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center justify-between p-4 bg-gray-50 rounded-lg';
                    div.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-file-alt text-2xl text-blue-500"></i>
                            <div>
                                <div class="font-semibold text-gray-900">${report.filename}</div>
                                <div class="text-sm text-gray-500">${new Date(report.modified * 1000).toLocaleString()}</div>
                            </div>
                        </div>
                        <a href="${report.url}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-download"></i> Download
                        </a>
                    `;
                    container.appendChild(div);
                });
            });
        }

        function runManualCheck() {
            const button = event.target.closest('button');
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Running...</span>';

            fetch('/api/monitoring/run-check?type=full', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                loadStatus();
                loadReports();
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-sync-alt"></i><span>Run Check</span>';
            })
            .catch(error => {
                console.error('Error running check:', error);
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-sync-alt"></i><span>Run Check</span>';
            });
        }

        function getStatusIcon(status, large = false) {
            const size = large ? 'text-6xl' : 'text-2xl';
            switch(status.toUpperCase()) {
                case 'OK':
                    return `<i class="fas fa-check-circle ${size} text-green-500"></i>`;
                case 'WARNING':
                    return `<i class="fas fa-exclamation-triangle ${size} text-yellow-500"></i>`;
                case 'ERROR':
                case 'CRITICAL':
                    return `<i class="fas fa-times-circle ${size} text-red-500"></i>`;
                default:
                    return `<i class="fas fa-question-circle ${size} text-gray-400"></i>`;
            }
        }

        function formatKey(key) {
            return key.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }
    </script>
</body>
</html>
