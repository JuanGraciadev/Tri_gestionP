<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReporteController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * GET /reportes
     * Main Reportes Dashboard with KPIs, Charts, Tables, and Export options.
     */
    public function index(Request $request): View
    {
        $filtro      = $request->input('filtro', '30dias');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin    = $request->input('fecha_fin');

        // Resolve dates
        $dateRange = $this->reportService->resolveDateRange($filtro, $fechaInicio, $fechaFin);
        $start     = $dateRange['start'];
        $end       = $dateRange['end'];

        // Gather metrics and analytics
        $kpis             = $this->reportService->getKPIs($start, $end);
        $ventasPorDia     = $this->reportService->getVentasPorDia($start, $end);
        $ventasPorEstado  = $this->reportService->getVentasPorEstado($start, $end);
        $ventasMetodoPago = $this->reportService->getVentasPorMetodoPago($start, $end);
        $topProductos     = $this->reportService->getTopProductos($start, $end, 10);
        $ventasCategoria  = $this->reportService->getVentasPorCategoria($start, $end);
        $estadoInventario = $this->reportService->getEstadoInventario();
        $disponibilidadCategorias = $this->reportService->getDisponibilidadPorCategoria();
        $produccion       = $this->reportService->getReporteProduccion();
        $lotes            = $this->reportService->getReporteLotes();

        return view('reportes.index', compact(
            'dateRange',
            'kpis',
            'ventasPorDia',
            'ventasPorEstado',
            'ventasMetodoPago',
            'topProductos',
            'ventasCategoria',
            'estadoInventario',
            'disponibilidadCategorias',
            'produccion',
            'lotes'
        ));
    }

    /**
     * Exportar reporte consolidado a formato CSV con codificación UTF-8 BOM para Excel.
     */
    public function exportarCSV(Request $request): StreamedResponse
    {
        $filtro      = $request->input('filtro', '30dias');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin    = $request->input('fecha_fin');

        $dateRange = $this->reportService->resolveDateRange($filtro, $fechaInicio, $fechaFin);
        $start     = $dateRange['start'];
        $end       = $dateRange['end'];

        $kpis             = $this->reportService->getKPIs($start, $end);
        $topProductos     = $this->reportService->getTopProductos($start, $end, 100);
        $ventasCategoria  = $this->reportService->getVentasPorCategoria($start, $end);
        $estadoInventario = $this->reportService->getEstadoInventario();
        $disponibilidad   = $this->reportService->getDisponibilidadPorCategoria();

        $filename = 'reporte_trigestion_' . $dateRange['filtro'] . '_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($dateRange, $kpis, $topProductos, $ventasCategoria, $estadoInventario, $disponibilidad) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 para apertura directa en Microsoft Excel sin problemas de acentos
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['TRIGESTION - REPORTE EJECUTIVO Y CONSOLIDADO']);
            fputcsv($handle, ['Periodo Evaluado:', $dateRange['label']]);
            fputcsv($handle, ['Fecha de Emision:', now()->format('d/m/Y H:i:s')]);
            fputcsv($handle, []);

            // KPIs
            fputcsv($handle, ['--- RESUMEN EJECUTIVO (KPIS) ---']);
            fputcsv($handle, ['Ingresos Totales', '$' . number_format($kpis['ingresos_totales'], 2)]);
            fputcsv($handle, ['Ventas Procesadas', $kpis['ventas_validas']]);
            fputcsv($handle, ['Productos Despachados (Uds)', $kpis['productos_vendidos']]);
            fputcsv($handle, ['Promedio por Venta', '$' . number_format($kpis['ticket_promedio'], 2)]);
            fputcsv($handle, ['Envases en Consumo', $kpis['devoluciones']['en_consumo']]);
            fputcsv($handle, ['Envases Devueltos', $kpis['devoluciones']['devueltos']]);
            fputcsv($handle, []);

            // Top Productos
            fputcsv($handle, ['--- TOP PRODUCTOS VENDIDOS ---']);
            fputcsv($handle, ['Producto', 'Precio Unitario', 'Unidades Vendidas', 'Ingresos Generados']);
            foreach ($topProductos as $p) {
                fputcsv($handle, [
                    $p['nombre'],
                    '$' . number_format($p['precio'], 2),
                    $p['total_unidades'],
                    '$' . number_format($p['total_ingresos'], 2),
                ]);
            }
            fputcsv($handle, []);

            // Ventas por Categoría
            fputcsv($handle, ['--- VENTAS POR CATEGORIA ---']);
            fputcsv($handle, ['Categoria', 'Unidades Vendidas', 'Total Ingresos']);
            foreach ($ventasCategoria as $vc) {
                fputcsv($handle, [
                    $vc['categoria_nombre'],
                    $vc['total_unidades'],
                    '$' . number_format($vc['total_ingresos'], 2),
                ]);
            }
            fputcsv($handle, []);

            // Disponibilidad por Categoría
            fputcsv($handle, ['--- DISPONIBILIDAD POR CATEGORIA ---']);
            fputcsv($handle, ['Categoria', 'Variedad de Productos', 'Stock Total', 'Disponibles', 'Bajo Stock', 'Agotados']);
            foreach ($disponibilidad as $disp) {
                fputcsv($handle, [
                    $disp['categoria'],
                    $disp['total_productos'],
                    $disp['stock_total'],
                    $disp['disponibles'],
                    $disp['bajo_stock'],
                    $disp['agotados'],
                ]);
            }
            fputcsv($handle, []);

            // Estado Inventario
            fputcsv($handle, ['--- ESTADO ACTUAL DEL INVENTARIO DE PRODUCTOS ---']);
            fputcsv($handle, ['Producto', 'Categoria', 'Precio', 'Stock Actual', 'Stock Minimo', 'Estado']);
            foreach ($estadoInventario as $inv) {
                fputcsv($handle, [
                    $inv['nombre'],
                    $inv['categoria'],
                    '$' . number_format($inv['precio'], 2),
                    $inv['stock'],
                    $inv['stock_minimo'] ?? 10,
                    $inv['estado'],
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar reporte consolidado a formato Excel (.xls compatible).
     */
    public function exportarExcel(Request $request): Response
    {
        $filtro      = $request->input('filtro', '30dias');
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin    = $request->input('fecha_fin');

        $dateRange = $this->reportService->resolveDateRange($filtro, $fechaInicio, $fechaFin);
        $start     = $dateRange['start'];
        $end       = $dateRange['end'];

        $kpis             = $this->reportService->getKPIs($start, $end);
        $topProductos     = $this->reportService->getTopProductos($start, $end, 100);
        $ventasCategoria  = $this->reportService->getVentasPorCategoria($start, $end);
        $estadoInventario = $this->reportService->getEstadoInventario();
        $disponibilidad   = $this->reportService->getDisponibilidadPorCategoria();

        $filename = 'reporte_trigestion_' . $dateRange['filtro'] . '_' . date('Ymd_His') . '.xls';

        $html = view('reportes.excel', compact(
            'dateRange',
            'kpis',
            'topProductos',
            'ventasCategoria',
            'estadoInventario',
            'disponibilidad'
        ))->render();

        return response($html, 200, [
            'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ]);
    }
}
