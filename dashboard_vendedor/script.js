document.addEventListener('DOMContentLoaded', function() {
    
    // --- Lógica para el menú desplegable del perfil ---
    const userProfileBtn = document.getElementById('userProfileBtn');
    const profileDropdownMenu = document.getElementById('profileDropdownMenu');
    const userProfileContainer = document.querySelector('.user-profile-container');

    if (userProfileBtn) {
        userProfileBtn.addEventListener('click', function(event) {
            event.stopPropagation();
            profileDropdownMenu.classList.toggle('show');
            userProfileContainer.classList.toggle('open');
        });
    }

    window.addEventListener('click', function(event) {
        if (profileDropdownMenu && profileDropdownMenu.classList.contains('show')) {
            profileDropdownMenu.classList.remove('show');
            userProfileContainer.classList.remove('open');
        }
    });

    // --- Lógica de los Gráficos ADAPTADA ---

    const commonChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        interaction: { mode: 'index', intersect: false },
        scales: {
            x: { grid: { display: false } },
            y: { 
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        if (value >= 1000) {
                            return '$' + value / 1000 + 'k';
                        }
                        return '$' + value;
                    }
                }
            }
        }
    };

    // 1. Gráfico de Resumen de Ingresos (antes Visitante Único)
    const revenueCtx = document.getElementById('revenueChart')?.getContext('2d');
    if (revenueCtx) {
        const revenueData = {
            mes: { 
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'], 
                ingresos: [1500, 2200, 1800, 2500, 2300, 2800, 3000, 2600, 3200, 3500, 3100, 4000], 
                ganancias: [500, 900, 700, 1100, 950, 1300, 1400, 1100, 1500, 1700, 1450, 2000] 
            },
            semana: { 
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'], 
                ingresos: [400, 850, 600, 950, 550, 1000, 1150], 
                ganancias: [150, 350, 250, 400, 200, 450, 500] 
            }
        };
        const revenueChart = new Chart(revenueCtx, {
            type: 'line', 
            data: { 
                labels: revenueData.mes.labels, 
                datasets: [
                    { label: 'Ingresos', data: revenueData.mes.ingresos, borderColor: '#7f56d9', backgroundColor: 'rgba(127, 86, 217, 0.1)', fill: true, tension: 0.4 }, 
                    { label: 'Ganancias', data: revenueData.mes.ganancias, borderColor: '#6c757d', backgroundColor: 'rgba(108, 117, 125, 0.1)', fill: true, tension: 0.4 }
                ] 
            },
            options: commonChartOptions
        });
        document.querySelectorAll('.revenue-summary-card .chart-controls button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelector('.revenue-summary-card .chart-controls .active').classList.remove('active');
                this.classList.add('active');
                const period = this.textContent.toLowerCase();
                const data = revenueData[period];
                revenueChart.data.labels = data.labels;
                revenueChart.data.datasets[0].data = data.ingresos;
                revenueChart.data.datasets[1].data = data.ganancias;
                revenueChart.update();
            });
        });
    }

    // 2. Gráfico de Pedidos por Día (antes Ingresos Semanales)
    const dailyOrdersCtx = document.getElementById('dailyOrdersChart')?.getContext('2d');
    if (dailyOrdersCtx) {
        new Chart(dailyOrdersCtx, { 
            type: 'bar', 
            data: { 
                labels: ['L', 'M', 'M', 'J', 'V', 'S', 'D'], 
                datasets: [{ 
                    label: 'Pedidos',
                    data: [40, 55, 62, 45, 70, 85, 90], 
                    backgroundColor: '#48d8b2', 
                    borderRadius: 4 
                }] 
            }, 
            options: {
                ...commonChartOptions,
                scales: { x: { grid: { display: false } }, y: { display: false } }
            } 
        });
    }

    // 3. Gráfico de Ventas por Categoría (antes Informe de Análisis)
    const categorySalesCtx = document.getElementById('categorySalesChart')?.getContext('2d');
    if (categorySalesCtx) {
        new Chart(categorySalesCtx, { 
            type: 'doughnut', 
            data: { 
                labels: ['Electrónicos', 'Ropa', 'Hogar', 'Accesorios'],
                datasets: [{
                    label: 'Ventas',
                    data: [12500, 8500, 6200, 4300],
                    backgroundColor: ['#7f56d9', '#ffc107', '#48d8b2', '#6c757d'],
                    hoverOffset: 4
                }]
            }, 
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }
    
    // Se eliminó la lógica de la gráfica 'salesReportChart' ya que fue reemplazada.
});