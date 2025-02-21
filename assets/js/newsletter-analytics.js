document.addEventListener('DOMContentLoaded', function() {
    // Chart configuration
    Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif';
    Chart.defaults.color = '#666';
    
    let growthChart, frequencyChart, categoryChart;
    const colors = {
        blue: 'rgb(59, 130, 246)',
        indigo: 'rgb(99, 102, 241)',
        green: 'rgb(16, 185, 129)',
        red: 'rgb(239, 68, 68)',
        gray: 'rgb(107, 114, 128)'
    };

    // Format numbers with K/M suffix
    function formatNumber(num) {
        if (num >= 1000000) {
            return (num / 1000000).toFixed(1) + 'M';
        }
        if (num >= 1000) {
            return (num / 1000).toFixed(1) + 'K';
        }
        return num.toString();
    }

    // Calculate percentage change
    function calculateChange(current, previous) {
        if (!previous) return null;
        return ((current - previous) / previous * 100).toFixed(1);
    }

    // Update stat card
    function updateStatCard(selector, number, change = null) {
        const card = document.querySelector(selector);
        if (!card) return;

        card.querySelector('.stat-number').textContent = formatNumber(number);
        
        const changeEl = card.querySelector('.stat-change');
        if (changeEl && change !== null) {
            const isPositive = change > 0;
            changeEl.className = `stat-change ${isPositive ? 'positive' : 'negative'}`;
            changeEl.innerHTML = `
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M${isPositive ? '12 7l-5 5M12 7l-5-5' : '7 7l5-5M7 7l5 5'}" clip-rule="evenodd" />
                </svg>
                ${Math.abs(change)}%
            `;
        }
    }

    // Initialize charts
    async function initializeCharts() {
        try {
            const [statsResponse, growthResponse, categoryResponse] = await Promise.all([
                fetch(ajaxurl + '?action=saxon_get_subscriber_stats&_ajax_nonce=' + saxonAnalytics.nonce),
                fetch(ajaxurl + '?action=saxon_get_growth_data&_ajax_nonce=' + saxonAnalytics.nonce),
                fetch(ajaxurl + '?action=saxon_get_category_stats&_ajax_nonce=' + saxonAnalytics.nonce)
            ]);

            const stats = await statsResponse.json();
            const growth = await growthResponse.json();
            const categories = await categoryResponse.json();

            if (stats.success && growth.success && categories.success) {
                updateStats(stats.data);
                createGrowthChart(growth.data);
                createFrequencyChart(stats.data.frequency);
                createCategoryChart(categories.data);
            }
        } catch (error) {
            console.error('Error fetching analytics data:', error);
        }
    }

    // Update statistics
    function updateStats(data) {
        updateStatCard('.total-subscribers', data.total);
        updateStatCard('.active-subscribers', data.total - data.unverified);
        updateStatCard('.this-month', data.this_month);
        
        const verificationRate = ((data.total - data.unverified) / data.total * 100).toFixed(1);
        updateStatCard('.conversion-rate', verificationRate + '%');
    }

    // Create Growth Chart
    function createGrowthChart(data) {
        const ctx = document.getElementById('growthChart').getContext('2d');
        
        if (growthChart) {
            growthChart.destroy();
        }

        growthChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => item.month),
                datasets: [{
                    label: 'New Subscribers',
                    data: data.map(item => item.new_subscribers),
                    borderColor: colors.blue,
                    backgroundColor: colors.blue + '20',
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Verified Subscribers',
                    data: data.map(item => item.verified_subscribers),
                    borderColor: colors.green,
                    backgroundColor: colors.green + '20',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: formatNumber
                        }
                    }
                }
            }
        });
    }

    // Create Frequency Chart
    function createFrequencyChart(data) {
        const ctx = document.getElementById('frequencyChart').getContext('2d');
        
        if (frequencyChart) {
            frequencyChart.destroy();
        }

        const labels = data.map(item => item.frequency.charAt(0).toUpperCase() + item.frequency.slice(1));
        const values = data.map(item => item.count);

        frequencyChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: [colors.blue, colors.indigo, colors.green]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Create Category Chart
    function createCategoryChart(data) {
        const ctx = document.getElementById('categoryChart').getContext('2d');
        
        if (categoryChart) {
            categoryChart.destroy();
        }

        const sortedData = Object.entries(data)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 10);

        categoryChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: sortedData.map(item => item[0]),
                datasets: [{
                    label: 'Subscribers',
                    data: sortedData.map(item => item[1]),
                    backgroundColor: colors.blue,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: formatNumber
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                }
            }
        });
    }

    // Initialize
    initializeCharts();

    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            if (growthChart) growthChart.resize();
            if (frequencyChart) frequencyChart.resize();
            if (categoryChart) categoryChart.resize();
        }, 250);
    });

    // Date range selector
    const dateRange = document.getElementById('dateRange');
    if (dateRange) {
        dateRange.addEventListener('change', async () => {
            try {
                const response = await fetch(ajaxurl + '?action=saxon_get_growth_data&range=' + dateRange.value + '&_ajax_nonce=' + saxonAnalytics.nonce);
                const data = await response.json();
                if (data.success) {
                    createGrowthChart(data.data);
                }
            } catch (error) {
                console.error('Error updating growth chart:', error);
            }
        });
    }
});