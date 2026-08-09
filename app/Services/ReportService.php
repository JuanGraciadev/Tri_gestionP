<?php

namespace App\Services;

use App\Models\Categoria;
use App\Models\DevolucionRetornables;
use App\Models\InventarioProductos;
use App\Models\Lote;
use App\Models\Produccion;
use App\Models\Producto;
use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Resolves start and end dates from a filter key or custom input.
     * Validates that $start <= $end.
     */
    public function resolveDateRange(string $filtroKey, ?string $fechaInicio = null, ?string $fechaFin = null): array
    {
        $today = Carbon::today();

        switch ($filtroKey) {
            case 'hoy':
                $start = $today->copy()->startOfDay();
                $end   = $today->copy()->endOfDay();
                break;
            case 'ayer':
                $start = $today->copy()->subDay()->startOfDay();
                $end   = $today->copy()->subDay()->endOfDay();
                break;
            case '7dias':
                $start = $today->copy()->subDays(6)->startOfDay();
                $end   = $today->copy()->endOfDay();
                break;
            case '30dias':
                $start = $today->copy()->subDays(29)->startOfDay();
                $end   = $today->copy()->endOfDay();
                break;
            case 'este_mes':
                $start = $today->copy()->startOfMonth();
                $end   = $today->copy()->endOfMonth();
                break;
            case 'mes_anterior':
                $start = $today->copy()->subMonth()->startOfMonth();
                $end   = $today->copy()->subMonth()->endOfMonth();
                break;
            case 'este_ano':
                $start = $today->copy()->startOfYear();
                $end   = $today->copy()->endOfYear();
                break;
            case 'personalizado':
                if ($fechaInicio && $fechaFin) {
                    $start = Carbon::parse($fechaInicio)->startOfDay();
                    $end   = Carbon::parse($fechaFin)->endOfDay();
                    if ($start->gt($end)) {
                        // Swap if initial date > final date
                        $temp  = $start;
                        $start = $end->copy()->startOfDay();
                        $end   = $temp->copy()->endOfDay();
                    }
                } else {
                    $start = $today->copy()->subDays(29)->startOfDay();
                    $end   = $today->copy()->endOfDay();
                }
                break;
            default: // Default 30 días
                $filtroKey = '30dias';
                $start     = $today->copy()->subDays(29)->startOfDay();
                $end       = $today->copy()->endOfDay();
                break;
        }

        return [
            'filtro'       => $filtroKey,
            'start'        => $start,
            'end'          => $end,
            'fecha_inicio' => $start->format('Y-m-d'),
            'fecha_fin'    => $end->format('Y-m-d'),
            'label'        => $this->getFilterLabel($filtroKey, $start, $end),
        ];
    }

    private function getFilterLabel(string $key, Carbon $start, Carbon $end): string
    {
        return match ($key) {
            'hoy'          => 'Hoy (' . $start->format('d/m/Y') . ')',
            'ayer'         => 'Ayer (' . $start->format('d/m/Y') . ')',
            '7dias'        => 'Últimos 7 días (' . $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y') . ')',
            '30dias'       => 'Últimos 30 días (' . $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y') . ')',
            'este_mes'     => 'Este mes (' . $start->format('M Y') . ')',
            'mes_anterior' => 'Mes anterior (' . $start->format('M Y') . ')',
            'este_ano'     => 'Este año (' . $start->format('Y') . ')',
            'personalizado'=> 'Rango personalizado (' . $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y') . ')',
            default        => 'Últimos 30 días',
        };
    }

    /**
     * Calculates general KPI metrics filtered by date range.
     */
    public function getKPIs(Carbon $start, Carbon $end): array
    {
        $startDateStr = $start->toDateString();
        $endDateStr   = $end->toDateString();

        // 1. Ventas válidas e ingresos (excluyendo 'Cancelado' y 'Pendiente' para ingresos reales, o considerando entregados)
        $ventasQuery = DB::table('venta')
            ->whereBetween('fecha', [$startDateStr, $endDateStr]);

        $ventasValidasQuery = (clone $ventasQuery)
            ->whereNotIn('estado', ['Cancelado', 'Pendiente']);

        $ventasValidas  = $ventasValidasQuery->count();
        $ingresosTotales= (float) $ventasValidasQuery->sum('total');

        // Total unidades vendidas
        $productosVendidos = (int) DB::table('detalle_venta as dv')
            ->join('venta as v', 'dv.id_venta', '=', 'v.id_venta')
            ->whereBetween('v.fecha', [$startDateStr, $endDateStr])
            ->whereNotIn('v.estado', ['Cancelado', 'Pendiente'])
            ->sum('dv.cantidad');

        // Ticket promedio
        $ticketPromedio = $ventasValidas > 0 ? round($ingresosTotales / $ventasValidas, 2) : 0;

        // 2. Producción total
        $produccionTotal = (int) DB::table('produccion')->sum('cantidad');

        // 3. Inventario status classification across active products
        $productos = Producto::where('estado', 1)->get();
        $disponibles = 0;
        $bajoStock   = 0;
        $agotados    = 0;

        foreach ($productos as $p) {
            $stock = StockService::disponible($p->id_producto);
            if ($stock > 5) {
                $disponibles++;
            } elseif ($stock > 0) {
                $bajoStock++;
            } else {
                $agotados++;
            }
        }

        // 4. Devoluciones & Retornables KPIs
        $balances = DevolucionRetornables::obtenerBalancesClientes();
        $totalEntregados = array_sum(array_column($balances, 'total_entregado'));
        $totalDevueltos  = array_sum(array_column($balances, 'total_devuelto'));
        $totalEnConsumo  = array_sum(array_column($balances, 'en_consumo'));

        return [
            'ventas_validas'     => $ventasValidas,
            'ingresos_totales'   => $ingresosTotales,
            'productos_vendidos' => $productosVendidos,
            'ticket_promedio'    => $ticketPromedio,
            'produccion_total'   => $produccionTotal,
            'inventario'         => [
                'disponibles' => $disponibles,
                'bajo_stock'  => $bajoStock,
                'agotados'     => $agotados,
                'total'        => $productos->count(),
            ],
            'devoluciones'       => [
                'entregados' => $totalEntregados,
                'devueltos'  => $totalDevueltos,
                'en_consumo' => $totalEnConsumo,
            ],
        ];
    }

    /**
     * Sales grouped by date for Chart.js Line Chart.
     */
    public function getVentasPorDia(Carbon $start, Carbon $end): array
    {
        $startDateStr = $start->toDateString();
        $endDateStr   = $end->toDateString();

        $rows = DB::table('venta')
            ->selectRaw('DATE(fecha) as fecha, COUNT(*) as cantidad, COALESCE(SUM(total), 0) as ingresos')
            ->whereBetween('fecha', [$startDateStr, $endDateStr])
            ->whereNotIn('estado', ['Cancelado', 'Pendiente'])
            ->groupBy(DB::raw('DATE(fecha)'))
            ->orderBy('fecha', 'ASC')
            ->get();

        $labels   = [];
        $cantidades = [];
        $ingresos = [];

        foreach ($rows as $r) {
            $labels[]     = Carbon::parse($r->fecha)->format('d/m');
            $cantidades[] = (int) $r->cantidad;
            $ingresos[]   = (float) $r->ingresos;
        }

        return [
            'labels'     => $labels,
            'cantidades' => $cantidades,
            'ingresos'   => $ingresos,
        ];
    }

    /**
     * Sales grouped by status for Chart.js Doughnut Chart.
     */
    public function getVentasPorEstado(Carbon $start, Carbon $end): array
    {
        $startDateStr = $start->toDateString();
        $endDateStr   = $end->toDateString();

        $rows = DB::table('venta')
            ->selectRaw('estado, COUNT(*) as cantidad, COALESCE(SUM(total), 0) as total')
            ->whereBetween('fecha', [$startDateStr, $endDateStr])
            ->groupBy('estado')
            ->get();

        $labels = [];
        $data   = [];
        $colors = [
            'Pendiente'  => '#f59e0b',
            'En Proceso' => '#3b82f6',
            'Entregado'  => '#10b981',
            'Cancelado'  => '#ef4444',
        ];
        $chartColors = [];

        foreach ($rows as $r) {
            $st           = $r->estado ?? 'Desconocido';
            $labels[]     = $st;
            $data[]       = (int) $r->cantidad;
            $chartColors[] = $colors[$st] ?? '#64748b';
        }

        return [
            'labels' => $labels,
            'data'   => $data,
            'colors' => $chartColors,
            'rows'   => json_decode(json_encode($rows), true),
        ];
    }

    /**
     * Sales grouped by Payment Method.
     */
    public function getVentasPorMetodoPago(Carbon $start, Carbon $end): array
    {
        $startDateStr = $start->toDateString();
        $endDateStr   = $end->toDateString();

        return DB::table('venta')
            ->selectRaw('COALESCE(metodo_pago, "Efectivo") as metodo, COUNT(*) as cantidad, COALESCE(SUM(total), 0) as total')
            ->whereBetween('fecha', [$startDateStr, $endDateStr])
            ->whereNotIn('estado', ['Cancelado', 'Pendiente'])
            ->groupBy('metodo')
            ->get()
            ->toArray();
    }

    /**
     * Top selling products ranking (Top 10).
     */
    public function getTopProductos(Carbon $start, Carbon $end, int $limit = 10): array
    {
        $startDateStr = $start->toDateString();
        $endDateStr   = $end->toDateString();

        $results = DB::table('detalle_venta as dv')
            ->join('producto as p', 'dv.id_producto', '=', 'p.id_producto')
            ->join('venta as v', 'dv.id_venta', '=', 'v.id_venta')
            ->select(
                'p.id_producto',
                'p.nombre',
                'p.precio',
                DB::raw('SUM(dv.cantidad) as total_unidades'),
                DB::raw('SUM(dv.cantidad * dv.precio_unitario) as total_ingresos')
            )
            ->whereBetween('v.fecha', [$startDateStr, $endDateStr])
            ->whereNotIn('v.estado', ['Cancelado', 'Pendiente'])
            ->groupBy('p.id_producto', 'p.nombre', 'p.precio')
            ->orderByDesc('total_unidades')
            ->limit($limit)
            ->get();

        return json_decode(json_encode($results), true);
    }

    /**
     * Sales and units aggregated by Category.
     */
    public function getVentasPorCategoria(Carbon $start, Carbon $end): array
    {
        $startDateStr = $start->toDateString();
        $endDateStr   = $end->toDateString();

        $results = DB::table('detalle_venta as dv')
            ->join('producto as p', 'dv.id_producto', '=', 'p.id_producto')
            ->leftJoin('categoria as c', 'p.id_categoria', '=', 'c.id_categoria')
            ->join('venta as v', 'dv.id_venta', '=', 'v.id_venta')
            ->select(
                DB::raw('COALESCE(c.nombre, "Sin Categoría") as categoria_nombre'),
                DB::raw('SUM(dv.cantidad) as total_unidades'),
                DB::raw('SUM(dv.cantidad * dv.precio_unitario) as total_ingresos')
            )
            ->whereBetween('v.fecha', [$startDateStr, $endDateStr])
            ->whereNotIn('v.estado', ['Cancelado', 'Pendiente'])
            ->groupBy(DB::raw('COALESCE(c.nombre, "Sin Categoría")'))
            ->orderByDesc('total_ingresos')
            ->get();

        return json_decode(json_encode($results), true);
    }

    /**
     * Detailed Inventory Status for all active products.
     * Classification: Normal (>5), Bajo Stock (1..5), Agotado (0).
     */
    public function getEstadoInventario(): array
    {
        return Producto::with('categoria')
            ->where('estado', 1)
            ->orderBy('nombre')
            ->get()
            ->map(function ($p) {
                $stock = StockService::disponible($p->id_producto);
                $estado = match (true) {
                    $stock > 5  => 'Normal',
                    $stock > 0  => 'Bajo Stock',
                    default     => 'Agotado',
                };
                $badgeStyle = match ($estado) {
                    'Normal'     => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'Bajo Stock' => 'bg-amber-100 text-amber-700 border-amber-200',
                    'Agotado'    => 'bg-rose-100 text-rose-700 border-rose-200',
                };
                return [
                    'id_producto' => $p->id_producto,
                    'nombre'      => $p->nombre,
                    'categoria'   => $p->categoria->nombre ?? 'General',
                    'precio'      => $p->precio,
                    'stock'       => $stock,
                    'estado'      => $estado,
                    'badge'       => $badgeStyle,
                ];
            })
            ->toArray();
    }

    /**
     * Production logs overview.
     */
    public function getReporteProduccion(): array
    {
        return Produccion::with(['producto', 'usuario'])
            ->orderByDesc('id_produccion')
            ->get()
            ->map(fn($p) => [
                'id_produccion'   => $p->id_produccion,
                'producto_nombre' => $p->producto->nombre ?? 'N/A',
                'cantidad'        => $p->cantidad,
                'descripcion'     => $p->descripcion,
                'usuario'         => $p->usuario->nombres ?? $p->usuario->name ?? 'N/A',
                'estado'          => $p->estado,
            ])
            ->toArray();
    }

    /**
     * Batches overview.
     */
    public function getReporteLotes(): array
    {
        return Lote::with(['usuario', 'detalles'])
            ->orderByDesc('id_lote')
            ->get()
            ->map(fn($l) => [
                'id_lote'         => $l->id_lote,
                'codigo'          => $l->codigo,
                'fecha_recepcion' => optional($l->fecha_recepcion)->format('d/m/Y') ?? 'N/A',
                'usuario'         => $l->usuario->nombres ?? $l->usuario->name ?? 'N/A',
                'total_detalles'  => $l->detalles->count(),
            ])
            ->toArray();
    }
}
