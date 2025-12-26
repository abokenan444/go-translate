<template>
    <div class="monitoring-dashboard">
        <div class="dashboard-header">
            <h2>Real-Time System Monitoring</h2>
            <div class="health-status" :class="systemHealth">
                <span class="status-indicator"></span>
                {{ systemHealth.toUpperCase() }}
            </div>
        </div>

        <!-- System Metrics -->
        <div class="metrics-grid">
            <metric-card
                title="Active Users"
                :value="metrics.active_users"
                icon="users"
                :trend="calculateTrend('active_users')"
            />
            <metric-card
                title="Active Translations"
                :value="metrics.active_translations"
                icon="file-text"
                :trend="calculateTrend('active_translations')"
            />
            <metric-card
                title="Avg Response Time"
                :value="`${metrics.avg_response_time}ms`"
                icon="clock"
                :trend="calculateTrend('avg_response_time', true)"
            />
            <metric-card
                title="Error Rate"
                :value="`${metrics.error_rate}%`"
                icon="alert-triangle"
                :trend="calculateTrend('error_rate', true)"
                :alert="metrics.error_rate > 5"
            />
        </div>

        <!-- Services Status -->
        <div class="services-status">
            <h3>Services Health</h3>
            <div class="services-grid">
                <service-card
                    v-for="(service, name) in services"
                    :key="name"
                    :name="name"
                    :status="service.status"
                    :latency="service.latency_ms"
                    :details="service"
                />
            </div>
        </div>

        <!-- Real-Time Chart -->
        <div class="charts-section">
            <h3>Performance Metrics (Last Hour)</h3>
            <line-chart
                :data="chartData"
                :options="chartOptions"
            />
        </div>

        <!-- Recent Events -->
        <div class="events-section">
            <h3>Recent Events</h3>
            <event-list :events="recentEvents" />
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { monitor } from '@/monitoring';
import MetricCard from '@/Components/Monitoring/MetricCard.vue';
import ServiceCard from '@/Components/Monitoring/ServiceCard.vue';
import LineChart from '@/Components/Charts/LineChart.vue';
import EventList from '@/Components/Monitoring/EventList.vue';

const metrics = ref({
    active_users: 0,
    active_translations: 0,
    avg_response_time: 0,
    error_rate: 0,
});

const services = ref({});
const recentEvents = ref([]);
const chartData = ref({
    labels: [],
    datasets: [],
});

const systemHealth = computed(() => {
    if (metrics.value.error_rate > 10) return 'critical';
    if (metrics.value.error_rate > 5) return 'degraded';
    return 'healthy';
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        y: {
            beginAtZero: true
        }
    },
    plugins: {
        legend: {
            display: true,
            position: 'top',
        }
    }
};

let updateInterval;

const fetchSystemStatus = async () => {
    try {
        const response = await axios.get('/api/v1/health');
        const data = response.data;

        if (data.metrics) {
            metrics.value = data.metrics;
        }

        if (data.services) {
            services.value = data.services;
        }

        updateChartData();
    } catch (error) {
        console.error('Failed to fetch system status:', error);
    }
};

const updateChartData = () => {
    const now = new Date();
    const timeLabel = now.toLocaleTimeString();

    // Update chart data
    if (chartData.value.labels.length >= 60) {
        chartData.value.labels.shift();
        chartData.value.datasets.forEach(dataset => dataset.data.shift());
    }

    chartData.value.labels.push(timeLabel);

    // Response time dataset
    if (!chartData.value.datasets[0]) {
        chartData.value.datasets[0] = {
            label: 'Response Time (ms)',
            data: [],
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        };
    }
    chartData.value.datasets[0].data.push(metrics.value.avg_response_time);

    // Error rate dataset
    if (!chartData.value.datasets[1]) {
        chartData.value.datasets[1] = {
            label: 'Error Rate (%)',
            data: [],
            borderColor: 'rgb(255, 99, 132)',
            tension: 0.1
        };
    }
    chartData.value.datasets[1].data.push(metrics.value.error_rate);
};

const calculateTrend = (metricName, inverse = false) => {
    // Simplified trend calculation
    // In production, compare with previous values
    return 'neutral';
};

const setupRealTimeMonitoring = () => {
    // Subscribe to real-time metric updates
    monitor.subscribe('metric.updated', (metric) => {
        if (metrics.value.hasOwnProperty(metric.name)) {
            metrics.value[metric.name] = metric.value;
        }
        updateChartData();
    });

    // Subscribe to document status updates
    monitor.subscribe('document.status', (event) => {
        recentEvents.value.unshift({
            type: 'document_status',
            message: `Document ${event.document_id} status changed to ${event.status}`,
            timestamp: new Date(),
        });

        // Keep only last 20 events
        if (recentEvents.value.length > 20) {
            recentEvents.value = recentEvents.value.slice(0, 20);
        }
    });
};

onMounted(() => {
    fetchSystemStatus();
    setupRealTimeMonitoring();

    // Update every 30 seconds
    updateInterval = setInterval(fetchSystemStatus, 30000);
});

onUnmounted(() => {
    if (updateInterval) {
        clearInterval(updateInterval);
    }
});
</script>

<style scoped>
.monitoring-dashboard {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.health-status {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
}

.health-status.healthy {
    background: #d4edda;
    color: #155724;
}

.health-status.degraded {
    background: #fff3cd;
    color: #856404;
}

.health-status.critical {
    background: #f8d7da;
    color: #721c24;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: currentColor;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.services-status,
.charts-section,
.events-section {
    background: white;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

h3 {
    margin: 0 0 1rem 0;
    color: #333;
}
</style>
