class RealTimeUpdater {
    constructor() {
        this.init();
    }
    
    init() {
        this.updateStats();
        this.setupEventSource();
        setInterval(() => this.updateStats(), 30000); // Mise à jour toutes les 30s
    }
    
    updateStats() {
        fetch('/admin/api/dashboard_stats.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelectorAll('.stat-card').forEach(card => {
                        const type = card.dataset.type;
                        if (data.data[type]) {
                            card.querySelector('.stat-value').textContent = data.data[type];
                        }
                    });
                    document.getElementById('last-update').textContent = data.last_update;
                }
            });
    }
    
    setupEventSource() {
        const eventSource = new EventSource('/admin/api/events.php');
        
        eventSource.onmessage = (e) => {
            const data = JSON.parse(e.data);
            if (data.type === 'notification') {
                this.showNotification(data.message);
                this.updateStats();
            }
        };
    }
    
    showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'flash-notification';
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 500);
        }, 5000);
    }
}

new RealTimeUpdater();