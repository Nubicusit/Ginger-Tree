import './bootstrap';
import Chart from 'chart.js/auto';

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Custom plugin to draw data labels
    const dataLabelsPlugin = {
        id: 'dataLabels',
        afterDatasetsDraw(chart, args, options) {
            const { ctx } = chart;
            chart.data.datasets.forEach((dataset, i) => {
                const meta = chart.getDatasetMeta(i);
                meta.data.forEach((element, index) => {
                    if (element.hidden) return;
                    
                    const dataValue = dataset.data[index];
                    const { x, y } = element.tooltipPosition();
                    
                    ctx.save();
                    ctx.font = '12px Inter';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    
                    // Determine text color based on dataset
                    if (chart.canvas.id === 'leadChart') {
                        ctx.fillStyle = '#374151'; 
                    } else {
                        ctx.fillStyle = '#ffffff';
                    }
                    
                    ctx.fillText(dataValue, x, y);
                    ctx.restore();
                });
            });
        }
    };

    // Lead Conversion Chart
    const leadChartElement = document.getElementById('leadChart');
    if (leadChartElement) {
        const leadCtx = leadChartElement.getContext('2d');
        new Chart(leadCtx, {
            type: 'doughnut',
            data: {
                labels: ['Cold Call', 'Email', 'Email Campaign', 'Web', 'Phone Inquiry', 'Referral', 'Other', 'Other'],
                datasets: [{
                    data: [14, 5, 3, 3, 4, 4, 7, 7],
                    backgroundColor: [
                        '#42A5F5', // Web - Blue (14)
                        '#FFA726', // Cold Call - Orange (5)
                        '#FFB74D', // Email - Light Orange (3)
                        '#4DD0E1', // Email Campaign - Cyan (3)
                        '#26A69A', // Phone Inquiry - Teal (4)
                        '#AB47BC', // Referral - Purple (4)
                        '#EF5350', // Other - Red (7)
                        '#EF5350'  // Other - Red (7)
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                devicePixelRatio: window.devicePixelRatio || 1,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                cutout: '60%'
            },
            plugins: [{
                id: 'centerText',
                afterDraw: function(chart) {
                    const ctx = chart.ctx;
                    const centerX = chart.chartArea.left + (chart.chartArea.right - chart.chartArea.left) / 2;
                    const centerY = chart.chartArea.top + (chart.chartArea.bottom - chart.chartArea.top) / 2;
                    
                    ctx.save();
                    ctx.font = 'bold 16px Inter';
                    ctx.fillStyle = '#1F2937';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText('TOTAL 51', centerX, centerY - 10);
                    ctx.font = 'bold 14px Inter';
                    ctx.fillText('LEADS', centerX, centerY + 10);
                    ctx.restore();
                }
            }, dataLabelsPlugin]
        });
    }

    // Project Status Chart
    const projectChartElement = document.getElementById('projectChart');
    if (projectChartElement) {
        const projectCtx = projectChartElement.getContext('2d');
        new Chart(projectCtx, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'In Progress', 'Completed'],
                datasets: [{
                    data: [15, 18, 12],
                    backgroundColor: [
                        '#9CCC65', // Active - Green
                        '#42A5F5', // In Progress - Blue
                        '#FFA726'  // Completed - Orange
                    ],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                devicePixelRatio: window.devicePixelRatio || 1,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                cutout: '60%'
            },
            plugins: [{
                id: 'centerText',
                afterDraw: function(chart) {
                    const ctx = chart.ctx;
                    const centerX = chart.chartArea.left + (chart.chartArea.right - chart.chartArea.left) / 2;
                    const centerY = chart.chartArea.top + (chart.chartArea.bottom - chart.chartArea.top) / 2;
                    
                    ctx.save();
                    ctx.font = 'bold 36px Inter';
                    ctx.fillStyle = '#6B7280';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText('45', centerX, centerY - 10);
                    ctx.font = '14px Inter';
                    ctx.fillStyle = '#9CA3AF';
                    ctx.fillText('Total Projects', centerX, centerY + 20);
                    ctx.restore();
                }
            }, dataLabelsPlugin]
        });
    }

    // Handle window resize for better responsiveness on Mac
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Charts will automatically resize due to responsive: true
        }, 250);
    });
});