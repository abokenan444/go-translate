import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
    wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// System Metrics Monitoring
export class RealTimeMonitor {
    constructor() {
        this.metrics = {};
        this.listeners = [];
        this.initializeChannels();
    }

    initializeChannels() {
        // Listen to system metrics updates
        window.Echo.channel('system-metrics')
            .listen('SystemMetricUpdated', (event) => {
                this.handleMetricUpdate(event.metric);
            });

        // Listen to document status updates
        window.Echo.private(`user.${window.userId}`)
            .listen('DocumentStatusUpdated', (event) => {
                this.notifyListeners('document.status', event);
            });

        // Listen to translation assignments
        window.Echo.private(`translator.${window.userId}`)
            .listen('TranslationAssigned', (event) => {
                this.notifyListeners('translation.assigned', event);
            });

        // Listen to payment updates
        window.Echo.private(`partner.${window.partnerId}`)
            .listen('PayoutProcessed', (event) => {
                this.notifyListeners('payout.processed', event);
            });
    }

    handleMetricUpdate(metric) {
        this.metrics[metric.name] = metric;
        this.notifyListeners('metric.updated', metric);
    }

    subscribe(event, callback) {
        this.listeners.push({ event, callback });
    }

    notifyListeners(event, data) {
        this.listeners
            .filter(listener => listener.event === event)
            .forEach(listener => listener.callback(data));
    }

    getMetric(name) {
        return this.metrics[name];
    }

    getAllMetrics() {
        return this.metrics;
    }
}

// Initialize monitor
export const monitor = new RealTimeMonitor();

// Export for use in components
export default monitor;
