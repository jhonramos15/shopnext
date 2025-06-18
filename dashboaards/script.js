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

    // --- Lógica de los Gráficos ---

    const commonChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        interaction: { mode: 'index', intersect: false },
    };

    // 1. Gráfico de Visitante Único
    const uniqueVisitorCtx = document.getElementById('uniqueVisitorChart')?.getContext('2d');
    if (uniqueVisitorCtx) {
        const uniqueVisitorData = {
            mes: { labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'], visits: [100, 150, 100, 160, 110, 60, 80, 50, 90, 120, 90, 150], sessions: [80, 120, 80, 130, 90, 40, 60, 30, 70, 100, 70, 120] },
            semana: { labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'], visits: [40, 85, 60, 95, 55, 100, 115], sessions: [30, 65, 40, 75, 45, 80, 95] }
        };
        const uniqueVisitorChart = new Chart(uniqueVisitorCtx, {
            type: 'line', data: { labels: uniqueVisitorData.mes.labels, datasets: [{ label: 'Visitas de página', data: uniqueVisitorData.mes.visits, borderColor: '#007bff', backgroundColor: 'rgba(0, 123, 255, 0.1)', fill: true, tension: 0.4 }, { label: 'Sesiones', data: uniqueVisitorData.mes.sessions, borderColor: '#6c757d', backgroundColor: 'rgba(108, 117, 125, 0.1)', fill: true, tension: 0.4 }] },
            options: { ...commonChartOptions, scales: { x: { grid: { display: false } }, y: { beginAtZero: true } } }
        });
        document.querySelectorAll('.unique-visitor-card .chart-controls button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelector('.unique-visitor-card .chart-controls .active').classList.remove('active');
                this.classList.add('active');
                const period = this.textContent.toLowerCase();
                const data = uniqueVisitorData[period];
                uniqueVisitorChart.data.labels = data.labels;
                uniqueVisitorChart.data.datasets[0].data = data.visits;
                uniqueVisitorChart.data.datasets[1].data = data.sessions;
                uniqueVisitorChart.update();
            });
        });
    }

    // 2. Gráfico de Ingresos Semanales
    const weeklyIncomeCtx = document.getElementById('weeklyIncomeChart')?.getContext('2d');
    if (weeklyIncomeCtx) {
        new Chart(weeklyIncomeCtx, { type: 'bar', data: { labels: ['L', 'M', 'M', 'J', 'V', 'S', 'D'], datasets: [{ data: [100, 130, 180, 90, 50, 110, 150], backgroundColor: '#48d8b2', borderRadius: 4 }] }, options: { ...commonChartOptions, scales: { x: { grid: { display: false } }, y: { display: false } } } });
    }

    // 3. Gráfico de Informe de Análisis
    const companyFinanceCtx = document.getElementById('companyFinanceChart')?.getContext('2d');
    if (companyFinanceCtx) {
        new Chart(companyFinanceCtx, { type: 'line', data: { labels: ['Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'], datasets: [{ data: [130, 80, 150, 90, 140, 60, 110], borderColor: '#ffc107', backgroundColor: 'rgba(255, 193, 7, 0.1)', fill: true, tension: 0.4 }] }, options: { ...commonChartOptions, scales: { x: { display: false }, y: { display: false } } } });
    }
    
    // 4. Gráfico de Informe de Ventas
    const salesReportCtx = document.getElementById('salesReportChart')?.getContext('2d');
    if (salesReportCtx) {
        const salesReportData = {
            hoy: { labels: ['Mañana', 'Tarde', 'Noche'], income: [1200, 1900, 900], cost: [400, 600, 500], net: '$2,500' },
            semana: { labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie'], income: [3000, 4200, 3500, 5000, 4500], cost: [1000, 1500, 1200, 2000, 1800], net: '$18,500' },
            mes: { labels: ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4'], income: [25000, 29000, 32000, 35000], cost: [10000, 12000, 14000, 15000], net: '$80,000' },
            año: { labels: ['Q1', 'Q2', 'Q3', 'Q4'], income: [120000, 150000, 110000, 180000], cost: [50000, 60000, 45000, 75000], net: '$230,000' }
        };
        const salesReportChart = new Chart(salesReportCtx, {
            type: 'bar', data: { labels: salesReportData.año.labels, datasets: [{ label: 'Ingreso', data: salesReportData.año.income, backgroundColor: '#FFC107', borderRadius: 4 }, { label: 'Costo de ventas', data: salesReportData.año.cost, backgroundColor: '#007bff', borderRadius: 4 }] },
            options: { ...commonChartOptions, scales: { x: { grid: { display: false } }, y: { beginAtZero: true, ticks: { callback: value => (value / 1000) + 'k' } } } }
        });
        const salesControls = document.querySelectorAll('.sales-report-card .chart-controls button');
        const netBenefitAmountEl = document.querySelector('.sales-report-card .net-benefit-amount');
        salesControls.forEach(button => {
            button.addEventListener('click', function() {
                salesControls.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                const period = this.textContent.toLowerCase();
                const newData = salesReportData[period];
                salesReportChart.data.labels = newData.labels;
                salesReportChart.data.datasets[0].data = newData.income;
                salesReportChart.data.datasets[1].data = newData.cost;
                const maxIncome = Math.max(...newData.income, ...newData.cost);
                salesReportChart.options.scales.y.max = maxIncome * 1.2;
                salesReportChart.update();
                if (netBenefitAmountEl) { netBenefitAmountEl.textContent = newData.net; }
            });
        });
    }
});