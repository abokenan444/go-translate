<template>
    <div class="monitoring-dashboard">
        <div class="metrics-grid">
            <!-- System Health Overview -->
            <div class="metric-card">
                <h3>System Health</h3>
                <div class="health-status" :class="systemHealth">
                    {{ systemHealth }}
                </div>
                <div class="services-status">
                    <div v-for="(service, name) in services" :key="name" class="service-item">
                        <span class="service-name">{{ name }}</span>
                        <span class="service-status" :class="service.status">
                            {{ service.status }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="metric-card">
                <h3>Active Users</h3>
                <div class="metric-value">{{ metrics.active_users || 0 }}</div>
                <div class="metric-label">Currently Online</div>
            </div>

            <!-- Active Translations -->
            <div class="metric-card">
                <h3>Active Translations</h3>
                <div class="metric-value">{{ metrics.active_translations || 0 }}</div>
                <div class="metric-label">In Progress</div>
            </div>

            <!-- Response Time -->
            <div class="metric-card">
                <h3>Avg Response Time</h3>
                <div class="metric-value">{{ metrics.avg_response_time || 0 }}ms</div>
                <div class="metric-label">Last 5 Minutes</div>
            </div>

            <!-- Error Rate -->
            <div class="metric-card">
                <h3>Error Rate</h3>
                <div class="metric-value" :class="errorRateClass">
                    {{ metrics.error_rate || 0 }}%
                </div>
                <div class="metric-label">Current</div>
            </div>
        </div>

        <!-- Real-time Charts -->
        <div class="charts-section">
            <div class="chart-card">
                <h3>Response Time Trend</h3>
                <canvas ref="responseTimeChart"></canvas>
            </div>
            
            <div class="chart-card">
                <h3>Request Volume</h3>
                <canvas ref="requestVolumeChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="activity-section">
            <h3>Recent Activity</h3>
            <div class="activity-list">
                <div v-for="activity in recentActivities" :key="activity.id" class="activity-item">
                    <span class="activity-time">{{ formatTime(activity.timestamp) }}</span>
                    <span class="activity-type">{{ activity.type }}</span>
                    <span class="activity-message">{{ activity.message }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { monitor } from '@/monitoring';
import Chart from 'chart.js/auto';

export default {
    name: 'MonitoringDashboard',
    
    setup() {
        const systemHealth = ref('loading');
        const services = ref({});
        const metrics = ref({});
        const recentActivities = ref([]);
        const responseTimeChart = ref(null);
        const requestVolumeChart = ref(null);
        
        let chartInstances = {};

        const errorRateClass = computed(() => {
            const rate = metrics.value.error_rate || 0;
            if (rate > 5) return 'error';
            if (rate > 2) return 'warning';
            return 'success';
        });

        const loadSystemStatus = async () => {
            try {
                const response = await fetch('/api/v1/health');
                const data = await response.json();
                
                systemHealth.value = data.health;
                services.value = data.services;
                metrics.value = data.metrics;
            } catch (error) {
                console.error('Failed to load system status:', error);
                systemHealth.value = 'error';
            }
        };

        const initCharts = () => {
            // Response Time Chart
            if (responseTimeChart.value) {
                const ctx = responseTimeChart.value.getContext('2d');
                chartInstances.responseTime = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Response Time (ms)',
                            data: [],
                            borderColor: '#3b82f6',
                            tension: 0.4,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Request Volume Chart
            if (requestVolumeChart.value) {
                const ctx = requestVolumeChart.value.getContext('2d');
                chartInstances.requestVolume = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Requests',
                            data: [],
                            backgroundColor: '#10b981',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            }
        };

        const updateCharts = (metric) => {
            if (metric.name === 'response_time' && chartInstances.responseTime) {
                const chart = chartInstances.responseTime;
                const timestamp = new Date(metric.timestamp).toLocaleTimeString();
                
                chart.data.labels.push(timestamp);
                chart.data.datasets[0].data.push(metric.value);
                
                // Keep only last 20 data points
                if (chart.data.labels.length > 20) {
                    chart.data.labels.shift();
                    chart.data.datasets[0].data.shift();
                }
                
                chart.update();
            }
        };

        const formatTime = (timestamp) => {
            return new Date(timestamp).toLocaleTimeString();
        };

        onMounted(() => {
            loadSystemStatus();
            initCharts();

            // Subscribe to real-time updates
            monitor.subscribe('metric.updated', (metric) => {
                if (metric.name in metrics.value) {
                    metrics.value[metric.name] = metric.value;
                }
                updateCharts(metric);
            });

            // Refresh every 30 seconds
            const interval = setInterval(loadSystemStatus, 30000);
            
            onUnmounted(() => {
                clearInterval(interval);
                // Destroy charts
                Object.values(chartInstances).forEach(chart => chart.destroy());
            });
        });

        return {
            systemHealth,
            services,
            metrics,
            recentActivities,
            errorRateClass,
            responseTimeChart,
            requestVolumeChart,
            formatTime,
        };
    }
};
</script>

<style scoped>
.monitoring-dashboard {
    padding: 20px;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.metric-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.metric-card h3 {
    margin: 0 0 15px 0;
    font-size: 14px;
    color: #6b7280;
    text-transform: uppercase;
}

.metric-value {
    font-size: 36px;
    font-weight: bold;
    margin-bottom: 5px;
}

.metric-value.error {
    color: #ef4444;
}

.metric-value.warning {
    color: #f59e0b;
}

.metric-value.success {
    color: #10b981;
}

.metric-label {
    font-size: 14px;
    color: #9ca3af;
}

.health-status {
    font-size: 24px;
    font-weight: bold;
    padding: 10px;
    border-radius: 4px;
    text-align: center;
    text-transform: uppercase;
}

.health-status.healthy {
    background: #d1fae5;
    color: #065f46;
}

.health-status.degraded {
    background: #fef3c7;
    color: #92400e;
}

.health-status.error {
    background: #fee2e2;
    color: #991b1b;
}

.services-status {
    margin-top: 15px;
}

.service-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #e5e7eb;
}

.service-status {
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 12px;
}

.service-status.up {
    background: #d1fae5;
    color: #065f46;
}

.service-status.down {
    background: #fee2e2;
    color: #991b1b;
}

.charts-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.chart-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    height: 300px;
}

.chart-card h3 {
    margin: 0 0 15px 0;
    font-size: 16px;
}

.activity-section {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.activity-section h3 {
    margin: 0 0 15px 0;
}

.activity-list {
    max-height: 400px;
    overflow-y: auto;
}

.activity-item {
    display: grid;
    grid-template-columns: 100px 150px 1fr;
    gap: 15px;
    padding: 10px 0;
    border-bottom: 1px solid #e5e7eb;
}

.activity-time {
    color: #6b7280;
    font-size: 14px;
}

.activity-type {
    font-weight: 600;
    color: #3b82f6;
}
</style>
