<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket POS #{{ str_pad($venta->id_venta, 6, '0', STR_PAD_LEFT) }} - TRIGESTION</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=Outfit:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #0f172a;
            font-family: 'Courier Prime', Courier, monospace;
            color: #111827;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 12px;
            min-height: 100vh;
        }

        /* Barra de herramientas para pantalla */
        .pos-toolbar {
            width: 100%;
            max-width: 340px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            gap: 8px;
        }

        .btn {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 13px;
            padding: 10px 16px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-print {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35);
        }

        .btn-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(2, 132, 199, 0.5);
        }

        .btn-close {
            background: rgba(255, 255, 255, 0.1);
            color: #e2e8f0;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-close:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        /* Tirilla POS térmica (80mm) */
        .pos-ticket {
            width: 80mm;
            max-width: 100%;
            background: #ffffff;
            padding: 18px 14px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35), 0 1px 3px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            font-size: 12px;
            line-height: 1.35;
            position: relative;
        }

        /* Línea zigzag decorativa inferior de ticket térmico */
        .pos-ticket::after {
            content: "";
            position: absolute;
            bottom: -6px;
            left: 0;
            right: 0;
            height: 6px;
            background: radial-gradient(circle, transparent, transparent 50%, #ffffff 50%, #ffffff 100%);
            background-size: 12px 12px;
            display: none;
        }

        .ticket-center {
            text-align: center;
        }

        .ticket-right {
            text-align: right;
        }

        .brand-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            font-size: 20px;
            letter-spacing: -0.5px;
            color: #0369a1;
            line-height: 1.1;
            margin-bottom: 2px;
        }

        .brand-subtitle {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .company-info {
            font-size: 11px;
            color: #475569;
            margin-bottom: 8px;
        }

        .dashed-line {
            border-top: 1px dashed #94a3b8;
            margin: 8px 0;
        }

        .double-line {
            border-top: 2px solid #0f172a;
            margin: 8px 0;
        }

        .ticket-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            font-size: 14px;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 4px 0;
        }

        .meta-table {
            width: 100%;
            font-size: 11px;
            margin: 4px 0;
        }

        .meta-table td {
            padding: 1.5px 0;
            vertical-align: top;
        }

        .meta-label {
            color: #64748b;
            font-weight: bold;
            width: 32%;
        }

        .meta-value {
            color: #0f172a;
            font-weight: bold;
        }

        /* Tabla de productos POS */
        .items-table {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
            margin: 6px 0;
        }

        .items-table th {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 4px 0;
            border-bottom: 1px solid #0f172a;
        }

        .items-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .item-name {
            font-weight: bold;
            color: #0f172a;
        }

        .totals-table {
            width: 100%;
            font-size: 11px;
            margin: 6px 0;
        }

        .totals-table td {
            padding: 2px 0;
        }

        .total-highlight {
            font-family: 'Outfit', sans-serif;
            font-weight: 900;
            font-size: 16px;
            color: #0f172a;
            border-top: 1px dashed #94a3b8;
            padding-top: 4px !important;
        }

        .badge-pos {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            border: 1px solid #cbd5e1;
            margin-top: 2px;
        }

        .barcode {
            margin: 10px auto 4px auto;
            text-align: center;
            letter-spacing: 3px;
            font-size: 18px;
            font-weight: bold;
            font-family: monospace;
            background: repeating-linear-gradient(
                90deg,
                #000000,
                #000000 2px,
                #ffffff 2px,
                #ffffff 4px,
                #000000 4px,
                #000000 5px,
                #ffffff 5px,
                #ffffff 7px
            );
            height: 32px;
            width: 80%;
        }

        .footer-note {
            font-size: 10px;
            color: #475569;
            margin-top: 8px;
            line-height: 1.4;
        }

        /* Estilos de impresión para tirilla térmica POS */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .pos-toolbar {
                display: none !important;
            }

            .pos-ticket {
                box-shadow: none !important;
                border-radius: 0 !important;
                width: 100% !important;
                max-width: 80mm !important;
                padding: 4mm !important;
                margin: 0 auto !important;
            }

            @page {
                size: 80mm auto;
                margin: 2mm;
            }
        }
    </style>
</head>
<body>

    <!-- BARRA DE ACCIONES (NO IMPRIMIBLE) -->
    <div class="pos-toolbar">
        <a href="javascript:window.close()" class="btn btn-close">
            <i class="fas fa-arrow-left"></i> Volver / Cerrar
        </a>
        <button onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i> Imprimir Ticket POS
        </button>
    </div>

    <!-- COMPROBANTE TIPO TICKET POS TÉRMICO -->
    <div class="pos-ticket">
        <!-- Encabezado de la empresa -->
        <div class="ticket-center">
            <div class="brand-title">TRIGESTION</div>
            <div class="brand-subtitle">Agua Purificada Premium · Moova</div>
            <div class="company-info">
                NIT: 900.842.195-3 · Régimen Simplificado<br>
                Planta de Purificación y Distribución<br>
                Tel: (+57) 310 456 7890 · info@trigestion.com<br>
                Bogotá D.C., Colombia
            </div>
        </div>

        <div class="double-line"></div>

        <!-- Título del comprobante -->
        <div class="ticket-center">
            <div class="ticket-title">FACTURA DE VENTA POS</div>
            <div style="font-weight: bold; font-size: 13px;">No. POS-{{ str_pad($venta->id_venta, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <div class="dashed-line"></div>

        <!-- Metadatos de la venta -->
        <table class="meta-table">
            <tr>
                <td class="meta-label">FECHA:</td>
                <td class="meta-value">{{ $venta->fecha ? \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') : date('d/m/Y') }} {{ date('H:i') }}</td>
            </tr>
            <tr>
                <td class="meta-label">CAJERO:</td>
                <td class="meta-value">{{ $venta->usuario->nombres ?? $venta->usuario->name ?? 'Caja Principal' }}</td>
            </tr>
            <tr>
                <td class="meta-label">CLIENTE:</td>
                <td class="meta-value">{{ $venta->cliente_nombre ?? ($venta->cliente->usuario->nombres ?? 'Cliente Mostrador') }}</td>
            </tr>
            @if(!empty($venta->cliente_email) || !empty($venta->cliente?->usuario?->email))
            <tr>
                <td class="meta-label">CORREO:</td>
                <td class="meta-value">{{ $venta->cliente_email ?? $venta->cliente?->usuario?->email }}</td>
            </tr>
            @endif
            <tr>
                <td class="meta-label">PAGO:</td>
                <td class="meta-value">
                    {{ strtoupper($venta->metodo_pago ?? 'Efectivo') }}
                    <span class="badge-pos">{{ $venta->estado_pago ?? 'Confirmado' }}</span>
                </td>
            </tr>
            <tr>
                <td class="meta-label">ESTADO:</td>
                <td class="meta-value">
                    <span class="badge-pos" style="background:#f1f5f9;">{{ strtoupper($venta->estado ?? 'Entregado') }}</span>
                </td>
            </tr>
            @if(!empty($venta->notas))
            <tr>
                <td class="meta-label">NOTAS:</td>
                <td class="meta-value" style="font-style: italic;">{{ $venta->notas }}</td>
            </tr>
            @endif
        </table>

        <div class="dashed-line"></div>

        <!-- Detalle de productos vendidos -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="text-align: left; width: 45%;">PRODUCTO</th>
                    <th style="text-align: center; width: 15%;">CANT</th>
                    <th style="text-align: right; width: 20%;">PRECIO</th>
                    <th style="text-align: right; width: 20%;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $detalle)
                @php
                    $prodNombre = $detalle->producto->nombre ?? 'Producto #' . $detalle->id_producto;
                    $cant = (int) $detalle->cantidad;
                    $pUnit = (float) $detalle->precio_unitario;
                    $subt = $cant * $pUnit;
                @endphp
                <tr>
                    <td class="item-name">{{ $prodNombre }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $cant }}</td>
                    <td class="ticket-right">${{ number_format($pUnit, 2) }}</td>
                    <td class="ticket-right" style="font-weight: bold;">${{ number_format($subt, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="dashed-line"></div>

        <!-- Totales de la tirilla -->
        <table class="totals-table">
            @php
                $totalVenta = (float) $venta->total;
                $totalItems = $venta->detalles->sum('cantidad');
            @endphp
            <tr>
                <td>Total Artículos:</td>
                <td class="ticket-right" style="font-weight: bold;">{{ $totalItems }} un.</td>
            </tr>
            <tr>
                <td>Subtotal:</td>
                <td class="ticket-right">${{ number_format($totalVenta, 2) }}</td>
            </tr>
            <tr>
                <td>IVA (0% Exento):</td>
                <td class="ticket-right">$0.00</td>
            </tr>
            <tr class="total-highlight">
                <td style="font-size: 15px;">TOTAL A PAGAR:</td>
                <td class="ticket-right" style="font-size: 17px; font-weight: 900; color: #0369a1;">
                    ${{ number_format($totalVenta, 2) }}
                </td>
            </tr>
        </table>

        <div class="double-line"></div>

        <!-- Código de barras simulado / Pie de comprobante -->
        <div class="ticket-center">
            <div class="barcode"></div>
            <div style="font-size: 9px; letter-spacing: 2px; color: #64748b; margin-bottom: 6px;">
                *POS-{{ str_pad($venta->id_venta, 6, '0', STR_PAD_LEFT) }}*
            </div>

            <div class="footer-note">
                <strong>¡GRACIAS POR SU COMPRA!</strong><br>
                Conserve este comprobante para seguimiento, garantías o devolución de envases retornables.<br>
                <em>Tri-gestión POS · Sistema de Purificación de Agua</em>
            </div>
        </div>
    </div>

    <script>
        // Auto-lanzar diálogo de impresión si se especifica ?print=1 o directamente al abrir
        window.addEventListener('load', function() {
            const params = new URLSearchParams(window.location.search);
            if (params.get('print') === '1' || params.get('autoprint') === '1') {
                setTimeout(function() {
                    window.print();
                }, 300);
            }
        });
    </script>
</body>
</html>
