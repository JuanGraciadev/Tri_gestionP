<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body { font-family: Calibri, Arial, sans-serif; font-size: 11pt; }
        .header-title { font-size: 16pt; font-weight: bold; color: #0f172a; }
        .header-subtitle { font-size: 11pt; color: #475569; }
        .table-section { margin-top: 20px; border-collapse: collapse; width: 100%; }
        .table-section th { background-color: #4f46e5; color: #ffffff; font-weight: bold; padding: 8px; border: 1px solid #cbd5e1; text-align: left; }
        .table-section td { padding: 6px 8px; border: 1px solid #e2e8f0; }
        .section-header { font-size: 13pt; font-weight: bold; color: #1e293b; background-color: #f1f5f9; padding: 6px; }
        .kpi-title { font-weight: bold; background-color: #f8fafc; }
        .badge-normal { background-color: #d1fae5; color: #065f46; font-weight: bold; text-align: center; }
        .badge-bajo { background-color: #fef3c7; color: #92400e; font-weight: bold; text-align: center; }
        .badge-agotado { background-color: #fee2e2; color: #991b1b; font-weight: bold; text-align: center; }
        .num { text-align: right; }
        .center { text-align: center; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="6" class="header-title">TRIGESTION - REPORTE EJECUTIVO Y CONSOLIDADO</td>
        </tr>
        <tr>
            <td colspan="6" class="header-subtitle">Período: {{ $dateRange['label'] }} | Fecha de Emisión: {{ now()->format('d/m/Y H:i') }}</td>
        </tr>
        <tr><td></td></tr>

        <!-- KPIS -->
        <tr>
            <td colspan="6" class="section-header">RESUMEN EJECUTIVO (KPIS)</td>
        </tr>
        <tr>
            <td class="kpi-title">Ingresos Totales:</td>
            <td class="num">${{ number_format($kpis['ingresos_totales'], 2) }}</td>
            <td class="kpi-title">Ventas Procesadas:</td>
            <td class="center">{{ $kpis['ventas_validas'] }}</td>
            <td class="kpi-title">Ticket Promedio:</td>
            <td class="num">${{ number_format($kpis['ticket_promedio'], 2) }}</td>
        </tr>
        <tr>
            <td class="kpi-title">Productos Despachados:</td>
            <td class="num">{{ number_format($kpis['productos_vendidos']) }} uds</td>
            <td class="kpi-title">Envases en Consumo:</td>
            <td class="center">{{ $kpis['devoluciones']['en_consumo'] ?? 0 }}</td>
            <td class="kpi-title">Envases Devueltos:</td>
            <td class="center">{{ $kpis['devoluciones']['devueltos'] ?? 0 }}</td>
        </tr>
        <tr><td></td></tr>

        <!-- TOP PRODUCTOS -->
        <tr>
            <td colspan="6" class="section-header">TOP PRODUCTOS VENDIDOS</td>
        </tr>
        <tr style="background-color: #4f46e5; color: #ffffff;">
            <th>#</th>
            <th colspan="2">Producto</th>
            <th>Precio Unitario</th>
            <th>Unidades Vendidas</th>
            <th>Ingresos Totales</th>
        </tr>
        @forelse ($topProductos as $idx => $p)
        <tr>
            <td class="center">{{ $idx + 1 }}</td>
            <td colspan="2">{{ $p['nombre'] }}</td>
            <td class="num">${{ number_format($p['precio'], 2) }}</td>
            <td class="center">{{ $p['total_unidades'] }}</td>
            <td class="num">${{ number_format($p['total_ingresos'], 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="6">Sin datos en el período</td></tr>
        @endforelse
        <tr><td></td></tr>

        <!-- VENTAS POR CATEGORÍA -->
        <tr>
            <td colspan="6" class="section-header">VENTAS POR CATEGORÍA</td>
        </tr>
        <tr style="background-color: #6366f1; color: #ffffff;">
            <th colspan="3">Categoría</th>
            <th>Unidades Vendidas</th>
            <th colspan="2">Total Ingresos</th>
        </tr>
        @forelse ($ventasCategoria as $vc)
        <tr>
            <td colspan="3"><strong>{{ $vc['categoria_nombre'] }}</strong></td>
            <td class="center">{{ $vc['total_unidades'] }}</td>
            <td colspan="2" class="num">${{ number_format($vc['total_ingresos'], 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="6">Sin ventas por categoría en el período</td></tr>
        @endforelse
        <tr><td></td></tr>

        <!-- DISPONIBILIDAD POR CATEGORÍA -->
        <tr>
            <td colspan="6" class="section-header">DISPONIBILIDAD POR CATEGORÍA</td>
        </tr>
        <tr style="background-color: #0284c7; color: #ffffff;">
            <th>Categoría</th>
            <th>Variedad de Productos</th>
            <th>Stock Total</th>
            <th>Disponibles</th>
            <th>Bajo Stock</th>
            <th>Agotados</th>
        </tr>
        @forelse ($disponibilidad as $disp)
        <tr>
            <td><strong>{{ $disp['categoria'] }}</strong></td>
            <td class="center">{{ $disp['total_productos'] }}</td>
            <td class="center"><strong>{{ $disp['stock_total'] }}</strong></td>
            <td class="center badge-normal">{{ $disp['disponibles'] }}</td>
            <td class="center badge-bajo">{{ $disp['bajo_stock'] }}</td>
            <td class="center badge-agotado">{{ $disp['agotados'] }}</td>
        </tr>
        @empty
        <tr><td colspan="6">Sin datos de categorías</td></tr>
        @endforelse
        <tr><td></td></tr>

        <!-- INVENTARIO PRODUCTOS -->
        <tr>
            <td colspan="6" class="section-header">ESTADO ACTUAL DEL INVENTARIO DE PRODUCTOS</td>
        </tr>
        <tr style="background-color: #0d9488; color: #ffffff;">
            <th>Producto</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th>Stock Actual</th>
            <th>Stock Mínimo</th>
            <th>Estado</th>
        </tr>
        @forelse ($estadoInventario as $inv)
        <tr>
            <td>{{ $inv['nombre'] }}</td>
            <td>{{ $inv['categoria'] }}</td>
            <td class="num">${{ number_format($inv['precio'], 2) }}</td>
            <td class="center"><strong>{{ $inv['stock'] }}</strong></td>
            <td class="center">{{ $inv['stock_minimo'] ?? 10 }}</td>
            <td class="{{ $inv['estado'] === 'Normal' ? 'badge-normal' : ($inv['estado'] === 'Bajo Stock' ? 'badge-bajo' : 'badge-agotado') }}">
                {{ $inv['estado'] }}
            </td>
        </tr>
        @empty
        <tr><td colspan="6">Sin productos registrados</td></tr>
        @endforelse
    </table>
</body>
</html>
