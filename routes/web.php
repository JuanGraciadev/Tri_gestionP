<?php

use App\Http\Controllers\AdminUsuarioController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ChatIAController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\InventarioMateriaPrimaController;
use App\Http\Controllers\InventarioProductosController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\MateriaPrimaController;
use App\Http\Controllers\MisComprasController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PresentacionProductoController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\SolicitudDevolucionController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Rutas Públicas ───────────────────────────────────────────
Route::get('/', function () {
    return view('public.welcome');
});

// ── Dashboard Inteligente (Redirección por rol) ──────────────
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ── Perfil de Usuario (Autenticado) ──────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── ROL 1: Administrador ─────────────────────────────────────
Route::middleware(['auth', 'role:1'])->group(function () {
    // Usuarios Admin
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminUsuarioController::class, 'index'])->name('admin.dashboard');
        Route::post('/usuarios', [AdminUsuarioController::class, 'store'])->name('admin.usuarios.store');
        Route::put('/usuarios/{usuario}', [AdminUsuarioController::class, 'update'])->name('admin.usuarios.update');
        Route::post('/usuarios/{usuario}/toggle-estado', [AdminUsuarioController::class, 'toggleEstado'])->name('admin.usuarios.toggleEstado');
    });

    // Categorías
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
    Route::post('/categorias/{categoria}/toggle-estado', [CategoriaController::class, 'toggleEstado'])->name('categorias.toggleEstado');
    Route::post('/categorias/agregar-producto', [CategoriaController::class, 'agregarProducto'])->name('categorias.agregarProducto');

    // Productos
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    Route::post('/productos/{producto}/toggle-estado', [ProductoController::class, 'toggleEstado'])->name('productos.toggleEstado');

    // Ventas y Pedidos (Admin)
    Route::get('/ventas', [PedidoController::class, 'index'])->name('ventas.index');
    Route::get('/ventas/{id}', [PedidoController::class, 'show'])->name('ventas.show');
    Route::post('/ventas/cambiar-estado', [PedidoController::class, 'cambiarEstado'])->name('ventas.cambiarEstado');
    Route::post('/ventas/cambiar-estado-pago', [PedidoController::class, 'cambiarEstadoPago'])->name('ventas.cambiarEstadoPago');
    Route::post('/ventas/crear-pos', [PosController::class, 'crear'])->name('ventas.crearPOS');

    // Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/exportar-csv', [ReporteController::class, 'exportarCSV'])->name('reportes.exportar.csv');
    Route::get('/reportes/exportar-excel', [ReporteController::class, 'exportarExcel'])->name('reportes.exportar.excel');
});

// ── ROL 2: Trabajador (Operaciones y Planta) ─────────────────
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::prefix('trabajador')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'trabajador'])->name('trabajador.dashboard');
    });
});

// ── ROLES 1 Y 2: Operaciones de Planta e Inventarios (Admin & Trabajador) ────
Route::middleware(['auth', 'role:1,2'])->group(function () {
    // Producción
    Route::get('/produccion', [ProduccionController::class, 'index'])->name('produccion.index');
    Route::post('/produccion', [ProduccionController::class, 'store'])->name('produccion.store');
    Route::post('/produccion/{id}/finalizar', [ProduccionController::class, 'finalizar'])->name('produccion.finalizar');
    Route::post('/produccion/{id}/cancelar', [ProduccionController::class, 'cancelar'])->name('produccion.cancelar');

    // Inventarios
    Route::get('/inventario-mp', [InventarioMateriaPrimaController::class, 'index'])->name('inventario-mp.index');
    Route::get('/inventario-productos', [InventarioProductosController::class, 'index'])->name('inventario-productos.index');

    // Insumos y Materias Primas (Etiquetas, Bolsas para Pacas, Tapas)
    Route::get('/materias-primas', [MateriaPrimaController::class, 'index'])->name('materias-primas.index');
    Route::post('/materias-primas', [MateriaPrimaController::class, 'store'])->name('materias-primas.store');
    Route::put('/materias-primas/{materiaPrima}', [MateriaPrimaController::class, 'update'])->name('materias-primas.update');
    Route::delete('/materias-primas/{materiaPrima}', [MateriaPrimaController::class, 'destroy'])->name('materias-primas.destroy');
    Route::post('/materias-primas/entrada', [MateriaPrimaController::class, 'registrarEntrada'])->name('materias-primas.entrada');
    Route::post('/materias-primas/{materiaPrima}/toggle-estado', [MateriaPrimaController::class, 'toggleEstado'])->name('materias-primas.toggleEstado');

    // Lotes
    Route::get('/lotes', [LoteController::class, 'index'])->name('lotes.index');
    Route::post('/lotes', [LoteController::class, 'store'])->name('lotes.store');
    Route::put('/lotes/{lote}', [LoteController::class, 'update'])->name('lotes.update');
    Route::delete('/lotes/{lote}', [LoteController::class, 'destroy'])->name('lotes.destroy');
    Route::get('/lotes/{lote}/detalles', [LoteController::class, 'detalles'])->name('lotes.detalles');
    Route::post('/lotes/detalles', [LoteController::class, 'storeDetalle'])->name('lotes.detalles.store');
    Route::put('/lotes/detalles/{detalle}', [LoteController::class, 'updateDetalle'])->name('lotes.detalles.update');

    // Devoluciones
    Route::get('/devoluciones', [DevolucionController::class, 'index'])->name('devoluciones.index');
    Route::post('/devoluciones', [DevolucionController::class, 'store'])->name('devoluciones.store');
});

// ── ROL 3: Cliente ───────────────────────────────────────────
Route::middleware(['auth', 'role:3'])->group(function () {
    Route::prefix('cliente')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'cliente'])->name('cliente.dashboard');
    });

    // Catálogo y Compras
    Route::get('/catalogo', [ProductoController::class, 'catalogo'])->name('productos.catalogo');
    Route::get('/mis-compras', [MisComprasController::class, 'index'])->name('cliente.misCompras');

    // Carrito de Compras (AJAX & Form)
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::get('/carrito/obtener', [CarritoController::class, 'obtener'])->name('carrito.obtener');
    Route::post('/carrito/actualizar', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::post('/carrito/eliminar', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');
    Route::post('/carrito/finalizar', [CarritoController::class, 'finalizar'])->name('carrito.finalizar');
    Route::post('/ventas/confirmar', [CarritoController::class, 'finalizar'])->name('ventas.confirmar');
});

// ── Comprobantes y Facturación POS (Acceso Autenticado con Control de Permisos) ──
Route::middleware(['auth'])->group(function () {
    Route::get('/ventas/factura/{id_venta}', [PosController::class, 'obtenerFactura'])->name('ventas.factura');
});

// ── APIs Compartidas (Admin & Trabajador) ─────────────────────
Route::middleware(['auth', 'role:1,2'])->group(function () {
    // Materias Primas
    Route::get('/api/materias-primas', [MateriaPrimaController::class, 'index']);
    Route::post('/api/materias-primas', [MateriaPrimaController::class, 'store']);
    Route::put('/api/materias-primas/{materiaPrima}', [MateriaPrimaController::class, 'update']);
    Route::delete('/api/materias-primas/{materiaPrima}', [MateriaPrimaController::class, 'destroy']);
    Route::post('/api/materias-primas/entrada', [MateriaPrimaController::class, 'registrarEntrada']);
    Route::get('/api/materias-primas/alertas', [MateriaPrimaController::class, 'alertas']);
    Route::post('/api/materias-primas/{materiaPrima}/toggle-estado', [MateriaPrimaController::class, 'toggleEstado']);

    // Alertas de Productos Terminados
    Route::get('/api/productos/alertas', [ProductoController::class, 'alertas']);

    // Presentaciones
    Route::get('/api/presentaciones', [PresentacionProductoController::class, 'index']);
    Route::post('/api/presentaciones', [PresentacionProductoController::class, 'store']);
    Route::put('/api/presentaciones/{presentacion}', [PresentacionProductoController::class, 'update']);
    Route::delete('/api/presentaciones/{presentacion}', [PresentacionProductoController::class, 'destroy']);
    Route::get('/api/productos/{producto}/presentaciones', [PresentacionProductoController::class, 'porProducto']);
});

// ── APIs: Solicitudes de Devolución ───────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::post('/api/solicitudes-devolucion/solicitar', [SolicitudDevolucionController::class, 'solicitar']);
    Route::get('/api/solicitudes-devolucion/mis-solicitudes', [SolicitudDevolucionController::class, 'misSolicitudes']);
});

Route::middleware(['auth', 'role:1,2'])->group(function () {
    Route::get('/api/solicitudes-devolucion', [SolicitudDevolucionController::class, 'index']);
    Route::post('/api/solicitudes-devolucion/{solicitud}/procesar', [SolicitudDevolucionController::class, 'procesar']);
    Route::post('/api/solicitudes-devolucion/marcar-leidas', [SolicitudDevolucionController::class, 'marcarLeidas']);
});

// ── APIs: Asistente IA ─────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::post('/api/ia/chat', [ChatIAController::class, 'chat']);
    Route::get('/api/ia/status', [ChatIAController::class, 'status']);
});

require __DIR__.'/auth.php';
