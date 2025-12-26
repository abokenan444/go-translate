@extends('layouts.app')

@section('title', 'Monitoring Dashboard')

@section('content')
<div class="dashboard-container" x-data="monitoring()" x-init="init()">
    <div class="dashboard-header">
        <h1>📊 Monitoring Dashboard</h1>
        <div class="header-actions">
            <select x-model="timeRange" @change="refreshData()">
                <option value="1h">Last Hour</option>
                <option value="24h">Last 24 Hours</option>
                <option value="7d">Last 7 Days</option>
                <option value="30d">Last 30 Days</option>
            </select>
            <button @click="refreshData()" class="btn-refresh">🔄 Refresh</button>
        </div>
    </div>

    <!-- System Health -->
    <div class="health-status" :class="`status-${systemHealth.status}`">
        <div class="status-indicator"></div>
        <div class="status-text">
            <strong>System Status:</strong> 
            <span x-text="systemHealth.message"></span>
        </div>
        <div class="uptime">
            Uptime: <strong x-text="systemHealth.uptime"></strong>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-icon">📈</div>
            <div class="metric-content">
                <div class="metric-label">Total Requests</div>
                <div class="metric-value" x-text="metrics.totalRequests.toLocaleString()"></div>
                <div class="metric-change" :class="metrics.requestsChange >= 0 ? 'positive' : 'negative'">
                    <span x-text="metrics.requestsChange >= 0 ? '↑' : '↓'"></span>
                    <span x-text="Math.abs(metrics.requestsChange)"></span>%
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">⚡</div>
            <div class="metric-content">
                <div class="metric-label">Avg Response Time</div>
                <div class="metric-value"><span x-text="metrics.avgResponseTime"></span>ms</div>
                <div class="metric-change" :class="metrics.responseTimeChange <= 0 ? 'positive' : 'negative'">
                    <span x-text="metrics.responseTimeChange <= 0 ? '↓' : '↑'"></span>
                    <span x-text="Math.abs(metrics.responseTimeChange)"></span>%
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">🎯</div>
            <div class="metric-content">
                <div class="metric-label">Success Rate</div>
                <div class="metric-value"><span x-text="metrics.successRate"></span>%</div>
                <div class="metric-change positive">
                    <span>✓</span> Excellent
                </div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-icon">💾</div>
            <div class="metric-content">
                <div class="metric-label">Cache Hit Rate</div>
                <div class="metric-value"><span x-text="metrics.cacheHitRate"></span>%</div>
                <div class="metric-change positive">
                    <span>↑</span> <span x-text="metrics.cacheChange"></span>%
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="charts-row">
        <div class="chart-card">
            <h3>API Requests (Last 24h)</h3>
            <canvas id="requestsChart"></canvas>
        </div>

        <div class="chart-card">
            <h3>Response Time Distribution</h3>
            <canvas id="responseTimeChart"></canvas>
        </div>
    </div>

    <!-- Error Rate & Performance -->
    <div class="charts-row">
        <div class="chart-card">
            <h3>Error Rate</h3>
            <div class="error-stats">
                <div class="error-stat">
                    <span class="error-code">4xx</span>
                    <div class="error-bar">
                        <div class="error-fill" :style="`width: ${errors.client}%`"></div>
                    </div>
                    <span class="error-count" x-text="errors.client + '%'"></span>
                </div>
                <div class="error-stat">
                    <span class="error-code">5xx</span>
                    <div class="error-bar">
                        <div class="error-fill error" :style="`width: ${errors.server}%`"></div>
                    </div>
                    <span class="error-count" x-text="errors.server + '%'"></span>
                </div>
            </div>
        </div>

        <div class="chart-card">
            <h3>Top Languages</h3>
            <div class="language-stats">
                <template x-for="lang in topLanguages" :key="lang.code">
                    <div class="lang-stat">
                        <span class="lang-flag" x-text="lang.flag"></span>
                        <span class="lang-name" x-text="lang.name"></span>
                        <div class="lang-bar">
                            <div class="lang-fill" :style="`width: ${lang.percentage}%`"></div>
                        </div>
                        <span class="lang-count" x-text="lang.count"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Real-time Activity -->
    <div class="activity-section">
        <h3>Real-time Activity</h3>
        <div class="activity-feed">
            <template x-for="activity in recentActivity" :key="activity.id">
                <div class="activity-item" :class="`activity-${activity.type}`">
                    <div class="activity-icon" x-text="activity.icon"></div>
                    <div class="activity-content">
                        <div class="activity-text" x-text="activity.message"></div>
                        <div class="activity-time" x-text="activity.time"></div>
                    </div>
                    <div class="activity-status" :class="`status-${activity.status}`" x-text="activity.status"></div>
                </div>
            </template>
        </div>
    </div>

    <!-- Alerts -->
    <div class="alerts-section" x-show="alerts.length > 0">
        <h3>⚠️ Alerts & Warnings</h3>
        <div class="alerts-list">
            <template x-for="alert in alerts" :key="alert.id">
                <div class="alert-item" :class="`alert-${alert.severity}`">
                    <div class="alert-icon" x-text="alert.icon"></div>
                    <div class="alert-content">
                        <div class="alert-title" x-text="alert.title"></div>
                        <div class="alert-message" x-text="alert.message"></div>
                    </div>
                    <button @click="dismissAlert(alert.id)" class="alert-dismiss">✕</button>
                </div>
            </template>
        </div>
    </div>

    <!-- System Resources -->
    <div class="resources-grid">
        <div class="resource-card">
            <h4>💻 CPU Usage</h4>
            <div class="resource-meter">
                <div class="meter-fill" :style="`width: ${resources.cpu}%`" 
                     :class="resources.cpu > 80 ? 'danger' : resources.cpu > 60 ? 'warning' : 'normal'"></div>
            </div>
            <div class="resource-value" x-text="resources.cpu + '%'"></div>
        </div>

        <div class="resource-card">
            <h4>🧠 Memory Usage</h4>
            <div class="resource-meter">
                <div class="meter-fill" :style="`width: ${resources.memory}%`"
                     :class="resources.memory > 80 ? 'danger' : resources.memory > 60 ? 'warning' : 'normal'"></div>
            </div>
            <div class="resource-value" x-text="resources.memory + '%'"></div>
        </div>

        <div class="resource-card">
            <h4>💾 Redis Memory</h4>
            <div class="resource-meter">
                <div class="meter-fill" :style="`width: ${resources.redis}%`" 
                     :class="resources.redis > 80 ? 'danger' : resources.redis > 60 ? 'warning' : 'normal'"></div>
            </div>
            <div class="resource-value" x-text="resources.redisMemory"></div>
        </div>

        <div class="resource-card">
            <h4>🗄️ Database</h4>
            <div class="resource-meter">
                <div class="meter-fill" :style="`width: ${resources.database}%`"
                     :class="resources.database > 80 ? 'danger' : resources.database > 60 ? 'warning' : 'normal'"></div>
            </div>
            <div class="resource-value" x-text="resources.dbSize"></div>
        </div>
    </div>
</div>

<style>
.dashboard-container {
    padding: 24px;
    background: #f9fafb;
    min-height: 100vh;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.dashboard-header h1 {
    font-size: 28px;
    color: #111827;
}

.header-actions {
    display: flex;
    gap: 12px;
}

.header-actions select {
    padding: 8px 16px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    background: white;
}

.btn-refresh {
    padding: 8px 16px;
    background: #3b82f6;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.health-status {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 24px;
    background: white;
    border-radius: 12px;
    margin-bottom: 24px;
    border-left: 4px solid;
}

.health-status.status-healthy {
    border-color: #10b981;
}

.health-status.status-warning {
    border-color: #f59e0b;
}

.health-status.status-critical {
    border-color: #ef4444;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: currentColor;
    animation: pulse 2s infinite;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.metric-card {
    background: white;
    padding: 24px;
    border-radius: 12px;
    display: flex;
    gap: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.metric-icon {
    font-size: 40px;
}

.metric-label {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 8px;
}

.metric-value {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.metric-change {
    font-size: 14px;
    font-weight: 600;
}

.metric-change.positive {
    color: #10b981;
}

.metric-change.negative {
    color: #ef4444;
}

.charts-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.chart-card {
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.chart-card h3 {
    font-size: 18px;
    margin-bottom: 20px;
    color: #111827;
}

.error-stats,
.language-stats {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.error-stat,
.lang-stat {
    display: flex;
    align-items: center;
    gap: 12px;
}

.error-code {
    font-family: monospace;
    font-weight: 600;
    width: 50px;
}

.error-bar,
.lang-bar {
    flex: 1;
    height: 24px;
    background: #f3f4f6;
    border-radius: 4px;
    overflow: hidden;
}

.error-fill {
    height: 100%;
    background: #10b981;
    transition: width 0.3s;
}

.error-fill.error {
    background: #ef4444;
}

.lang-fill {
    height: 100%;
    background: #3b82f6;
    transition: width 0.3s;
}

.activity-section {
    background: white;
    padding: 24px;
    border-radius: 12px;
    margin-bottom: 24px;
}

.activity-feed {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 12px;
    border-bottom: 1px solid #f3f4f6;
}

.activity-icon {
    font-size: 24px;
}

.activity-content {
    flex: 1;
}

.activity-time {
    font-size: 12px;
    color: #9ca3af;
}

.activity-status {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.activity-status.status-success {
    background: #d1fae5;
    color: #065f46;
}

.alerts-section {
    margin-bottom: 24px;
}

.alert-item {
    display: flex;
    gap: 16px;
    padding: 16px;
    background: white;
    border-radius: 8px;
    margin-bottom: 12px;
    border-left: 4px solid;
}

.alert-item.alert-warning {
    border-color: #f59e0b;
}

.alert-item.alert-error {
    border-color: #ef4444;
}

.resources-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.resource-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
}

.resource-meter {
    height: 12px;
    background: #f3f4f6;
    border-radius: 6px;
    overflow: hidden;
    margin: 12px 0;
}

.meter-fill {
    height: 100%;
    transition: width 0.3s;
}

.meter-fill.normal {
    background: #10b981;
}

.meter-fill.warning {
    background: #f59e0b;
}

.meter-fill.danger {
    background: #ef4444;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function monitoring() {
    return {
        timeRange: '24h',
        systemHealth: {
            status: 'healthy',
            message: 'All systems operational',
            uptime: '99.98%'
        },
        metrics: {
            totalRequests: 45230,
            requestsChange: 12.5,
            avgResponseTime: 45,
            responseTimeChange: -8.3,
            successRate: 99.2,
            cacheHitRate: 66.67,
            cacheChange: 5.2
        },
        errors: {
            client: 0.5,
            server: 0.3
        },
        topLanguages: [
            { code: 'ar', flag: '🇸🇦', name: 'Arabic', percentage: 35, count: 15830 },
            { code: 'es', flag: '🇪🇸', name: 'Spanish', percentage: 25, count: 11307 },
            { code: 'fr', flag: '🇫🇷', name: 'French', percentage: 20, count: 9046 },
            { code: 'de', flag: '🇩🇪', name: 'German', percentage: 12, count: 5427 },
            { code: 'ja', flag: '🇯🇵', name: 'Japanese', percentage: 8, count: 3618 }
        ],
        recentActivity: [
            { id: 1, icon: '✅', message: 'Translation completed: 250 words', time: '2 min ago', status: 'success', type: 'translation' },
            { id: 2, icon: '🔄', message: 'Cache refreshed: 1.2M keys', time: '5 min ago', status: 'info', type: 'cache' },
            { id: 3, icon: '📊', message: 'Quality score: 95/100', time: '8 min ago', status: 'success', type: 'quality' },
            { id: 4, icon: '🌐', message: 'New API key created', time: '12 min ago', status: 'info', type: 'api' }
        ],
        alerts: [
            { id: 1, severity: 'warning', icon: '⚠️', title: 'High Memory Usage', message: 'Redis memory usage at 75%' }
        ],
        resources: {
            cpu: 45,
            memory: 62,
            redis: 75,
            redisMemory: '1.12 MB',
            database: 38,
            dbSize: '24.5 MB'
        },
        
        init() {
            this.initCharts();
            this.startAutoRefresh();
        },
        
        initCharts() {
            // Requests Chart
            new Chart(document.getElementById('requestsChart'), {
                type: 'line',
                data: {
                    labels: Array.from({length: 24}, (_, i) => `${i}:00`),
                    datasets: [{
                        label: 'Requests',
                        data: Array.from({length: 24}, () => Math.floor(Math.random() * 2000) + 1500),
                        borderColor: '#3b82f6',
                        tension: 0.4
                    }]
                }
            });
            
            // Response Time Chart
            new Chart(document.getElementById('responseTimeChart'), {
                type: 'bar',
                data: {
                    labels: ['0-50ms', '50-100ms', '100-200ms', '200-500ms', '>500ms'],
                    datasets: [{
                        label: 'Requests',
                        data: [35000, 8000, 1800, 400, 30],
                        backgroundColor: '#10b981'
                    }]
                }
            });
        },
        
        refreshData() {
            console.log('Refreshing data for:', this.timeRange);
        },
        
        dismissAlert(id) {
            this.alerts = this.alerts.filter(a => a.id !== id);
        },
        
        startAutoRefresh() {
            setInterval(() => {
                // Simulate real-time updates
                this.metrics.totalRequests += Math.floor(Math.random() * 10);
            }, 5000);
        }
    }
}
</script>
@endsection
