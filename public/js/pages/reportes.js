/**
 * Script de página: reportes.js
 */
function toggleCustomDates() {
            const select = document.getElementById('filtroSelect');
            const wrapper = document.getElementById('customDatesWrapper');
            if (select.value === 'personalizado') {
                wrapper.classList.remove('hidden');
            } else {
                wrapper.classList.add('hidden');
                document.getElementById('filterForm').submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            Chart.defaults.color = '#64748b';

            // ── Chart 1: Ventas por Día (Line) ──────────────────────────────
            @if (!empty($ventasPorDia['labels']))
            new Chart(document.getElementById('chartVentasDia').getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($ventasPorDia['labels']),
                    datasets: [
                        {
                            label: 'Ingresos ($)',
                            data: @json($ventasPorDia['ingresos']),
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            yAxisID: 'yIngresos',
                        },
                        {
                            label: 'Cantidad Ventas',
                            data: @json($ventasPorDia['cantidades']),
                            borderColor: '#38bdf8',
                            backgroundColor: 'transparent',
                            borderDash: [5, 5],
                            tension: 0.4,
                            borderWidth: 2,
                            yAxisID: 'yVentas',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yIngresos: { type: 'linear', position: 'left', beginAtZero: true, grid: { color: '#f1f5f9' } },
                        yVentas:   { type: 'linear', position: 'right', beginAtZero: true, grid: { display: false } },
                        x: { grid: { display: false } }
                    }
                }
            });
            @endif

            // ── Chart 2: Ventas por Estado (Doughnut) ────────────────────────
            @if (!empty($ventasPorEstado['labels']))
            new Chart(document.getElementById('chartVentasEstado').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: @json($ventasPorEstado['labels']),
                    datasets: [{
                        data: @json($ventasPorEstado['data']),
                        backgroundColor: @json($ventasPorEstado['colors']),
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { legend: { position: 'right' } }
                }
            });
            @endif

            // ── Chart 3: Ventas por Categoría (Bar) ─────────────────────────
            @php
                $catLabels = array_column($ventasCategoria, 'categoria_nombre');
                $catIngresos = array_column($ventasCategoria, 'total_ingresos');
            @endphp
            @if (!empty($catLabels))
            new Chart(document.getElementById('chartVentasCategoria').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($catLabels),
                    datasets: [{
                        label: 'Ingresos ($)',
                        data: @json($catIngresos),
                        backgroundColor: '#38bdf8',
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }
                }
            });
            @endif

            // ── Chart 4: Top Productos (Horizontal Bar) ─────────────────────
            @php
                $topLabels = array_column($topProductos, 'nombre');
                $topUnidades = array_column($topProductos, 'total_unidades');
            @endphp
            @if (!empty($topLabels))
            new Chart(document.getElementById('chartTopProductos').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: @json($topLabels),
                    datasets: [{
                        label: 'Unidades Vendidas',
                        data: @json($topUnidades),
                        backgroundColor: '#f59e0b',
                        borderRadius: 8,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true, grid: { color: '#f1f5f9' } }, y: { grid: { display: false } } }
                }
            });
            @endif
        });

        // Generador de PDF oficial
        async function generarPDF() {
            const overlay = document.getElementById('pdfOverlay');
            overlay.classList.add('active');

            try {
                const { jsPDF } = window.jspdf;
                const contenido = document.getElementById('reporteContenido');

                const canvas = await html2canvas(contenido, {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    backgroundColor: '#ffffff'
                });

                const imgData = canvas.toDataURL('image/jpeg', 1.0);
                const pdf = new jsPDF('p', 'mm', 'a4');
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

                pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);
                pdf.save('reporte_trigestion_' + (window.APP.reporteFiltro || 'general') + '.pdf');
            } catch (err) {
                console.error(err);
                alert('Hubo un error al generar el PDF.');
            } finally {
                overlay.classList.remove('active');
            }
        }
