$(document).ready(function() {
    // ==========================================
    // 3. RENDERIZADO DE GRÁFICOS (Chart.js)
    // ==========================================
    if (typeof DATOS_ESTADOS !== 'undefined' && DATOS_ESTADOS.length > 0) {
        const labelsEstados = DATOS_ESTADOS.map(d => d.estado);
        const dataEstados = DATOS_ESTADOS.map(d => d.cantidad);
        const coloresEstados = labelsEstados.map(estado => {
            if(estado === 'Operativo') return '#198754';
            if(estado === 'Dañado' || estado === 'Baja') return '#dc3545';
            if(estado === 'Mantenimiento') return '#ffc107';
            return '#6c757d'; 
        });

        new Chart(document.getElementById('graficoEstados'), {
            type: 'doughnut',
            data: {
                labels: labelsEstados,
                datasets: [{
                    data: dataEstados,
                    backgroundColor: coloresEstados,
                    borderWidth: 1
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    if (typeof DATOS_SEDES !== 'undefined' && DATOS_SEDES.length > 0) {
        const labelsSedes = DATOS_SEDES.map(d => d.sede);
        const dataSedes = DATOS_SEDES.map(d => d.cantidad);

        new Chart(document.getElementById('graficoSedes'), {
            type: 'bar',
            data: {
                labels: labelsSedes,
                datasets: [{
                    label: 'Equipos Registrados',
                    data: dataSedes,
                    backgroundColor: '#0d6efd',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                plugins: { legend: { display: false } }
            }
        });
    }
});