<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
            'produccion',
            'lotes'
        ));
    }
}
