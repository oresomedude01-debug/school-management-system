// ===== SIMPLE CHART LIBRARY - Vanilla JS =====
// No npm required! Pure JavaScript charts

class SimpleChart {
    constructor(canvasId, config) {
        this.canvas = document.getElementById(canvasId);
        if (!this.canvas) {
            console.error(`Canvas with id "${canvasId}" not found`);
            return;
        }
        this.ctx = this.canvas.getContext('2d');
        this.config = config;
        this.data = config.data || {};
        this.options = config.options || {};
        this.type = config.type || 'bar';

        // Set canvas size
        this.canvas.width = this.canvas.offsetWidth;
        this.canvas.height = this.canvas.offsetHeight;

        this.render();
    }

    render() {
        switch(this.type) {
            case 'bar':
                this.renderBarChart();
                break;
            case 'line':
                this.renderLineChart();
                break;
            case 'doughnut':
                this.renderDoughnutChart();
                break;
            case 'pie':
                this.renderPieChart();
                break;
            default:
                this.renderBarChart();
        }
    }

    renderBarChart() {
        const { labels, datasets } = this.data;
        const padding = 40;
        const width = this.canvas.width - padding * 2;
        const height = this.canvas.height - padding * 2;
        const barWidth = width / labels.length;
        const maxValue = Math.max(...datasets[0].data);

        // Clear canvas
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        // Draw bars with animation
        datasets[0].data.forEach((value, index) => {
            const barHeight = (value / maxValue) * height;
            const x = padding + index * barWidth;
            const y = this.canvas.height - padding - barHeight;

            // Draw bar
            const gradient = this.ctx.createLinearGradient(0, y, 0, y + barHeight);
            gradient.addColorStop(0, datasets[0].backgroundColor[index] || '#3B82F6');
            gradient.addColorStop(1, datasets[0].borderColor[index] || '#1E40AF');

            this.ctx.fillStyle = gradient;
            this.ctx.fillRect(x + 10, y, barWidth - 20, barHeight);

            // Draw label
            this.ctx.fillStyle = '#6B7280';
            this.ctx.font = '12px sans-serif';
            this.ctx.textAlign = 'center';
            this.ctx.fillText(labels[index], x + barWidth / 2, this.canvas.height - 20);

            // Draw value
            this.ctx.fillStyle = '#1F2937';
            this.ctx.font = 'bold 14px sans-serif';
            this.ctx.fillText(value, x + barWidth / 2, y - 10);
        });
    }

    renderLineChart() {
        const { labels, datasets } = this.data;
        const padding = 40;
        const width = this.canvas.width - padding * 2;
        const height = this.canvas.height - padding * 2;
        const pointSpacing = width / (labels.length - 1);
        const maxValue = Math.max(...datasets[0].data);

        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        // Draw line
        this.ctx.beginPath();
        this.ctx.strokeStyle = datasets[0].borderColor || '#3B82F6';
        this.ctx.lineWidth = 3;

        datasets[0].data.forEach((value, index) => {
            const x = padding + index * pointSpacing;
            const y = this.canvas.height - padding - (value / maxValue) * height;

            if (index === 0) {
                this.ctx.moveTo(x, y);
            } else {
                this.ctx.lineTo(x, y);
            }

            // Draw points
            this.ctx.fillStyle = datasets[0].backgroundColor || '#3B82F6';
            this.ctx.beginPath();
            this.ctx.arc(x, y, 6, 0, Math.PI * 2);
            this.ctx.fill();
            this.ctx.stroke();
        });

        this.ctx.stroke();

        // Draw labels
        labels.forEach((label, index) => {
            const x = padding + index * pointSpacing;
            this.ctx.fillStyle = '#6B7280';
            this.ctx.font = '12px sans-serif';
            this.ctx.textAlign = 'center';
            this.ctx.fillText(label, x, this.canvas.height - 20);
        });
    }

    renderDoughnutChart() {
        const { labels, datasets } = this.data;
        const centerX = this.canvas.width / 2;
        const centerY = this.canvas.height / 2;
        const radius = Math.min(centerX, centerY) - 40;
        const innerRadius = radius * 0.6;
        const total = datasets[0].data.reduce((a, b) => a + b, 0);

        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        let currentAngle = -Math.PI / 2;

        datasets[0].data.forEach((value, index) => {
            const sliceAngle = (value / total) * Math.PI * 2;

            // Draw slice
            this.ctx.beginPath();
            this.ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
            this.ctx.arc(centerX, centerY, innerRadius, currentAngle + sliceAngle, currentAngle, true);
            this.ctx.closePath();
            this.ctx.fillStyle = datasets[0].backgroundColor[index] || '#3B82F6';
            this.ctx.fill();

            // Draw label
            const labelAngle = currentAngle + sliceAngle / 2;
            const labelX = centerX + Math.cos(labelAngle) * (radius + 30);
            const labelY = centerY + Math.sin(labelAngle) * (radius + 30);

            this.ctx.fillStyle = '#1F2937';
            this.ctx.font = 'bold 12px sans-serif';
            this.ctx.textAlign = 'center';
            this.ctx.fillText(labels[index], labelX, labelY);
            this.ctx.font = '10px sans-serif';
            this.ctx.fillStyle = '#6B7280';
            this.ctx.fillText(value, labelX, labelY + 15);

            currentAngle += sliceAngle;
        });

        // Draw center text
        this.ctx.fillStyle = '#1F2937';
        this.ctx.font = 'bold 24px sans-serif';
        this.ctx.textAlign = 'center';
        this.ctx.fillText(total, centerX, centerY);
        this.ctx.font = '12px sans-serif';
        this.ctx.fillStyle = '#6B7280';
        this.ctx.fillText('Total', centerX, centerY + 20);
    }

    renderPieChart() {
        // Similar to doughnut but without inner radius
        const { labels, datasets } = this.data;
        const centerX = this.canvas.width / 2;
        const centerY = this.canvas.height / 2;
        const radius = Math.min(centerX, centerY) - 40;
        const total = datasets[0].data.reduce((a, b) => a + b, 0);

        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        let currentAngle = -Math.PI / 2;

        datasets[0].data.forEach((value, index) => {
            const sliceAngle = (value / total) * Math.PI * 2;

            this.ctx.beginPath();
            this.ctx.moveTo(centerX, centerY);
            this.ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
            this.ctx.closePath();
            this.ctx.fillStyle = datasets[0].backgroundColor[index] || '#3B82F6';
            this.ctx.fill();

            currentAngle += sliceAngle;
        });
    }
}

// Export for use in other files
window.SimpleChart = SimpleChart;
