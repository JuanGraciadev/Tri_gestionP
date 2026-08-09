<?php

use App\Http\Controllers\AdminUsuarioController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\InventarioMateriaPrimaController;
use App\Http\Controllers\InventarioProductosController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Smart redirector: sends authenticated user to their role-specific dashboard
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match ((int) $user?->id_rol) {
        1 => redirect()->route('admin.dashboard'),
        2 => redirect()->route('trabajador.dashboard'),
        3 => redirect()->route('cliente.dashboard'),
        default => redirect()->route('cliente.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// ── Profile Routes (All Authenticated) ───────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── ROLE 1: Administrador Routes ──────────────────────────────
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminUsuarioController::class, 'index'])->name('admin.dashboard');
        Route::post('/usuarios', [AdminUsuarioController::class, 'store'])->name('admin.usuarios.store');
        Route::put('/usuarios/{usuario}', [AdminUsuarioController::class, 'update'])->name('admin.usuarios.update');
        Route::get('/usuarios/{usuario}/toggle-estado', [AdminUsuarioController::class, 'toggleEstado'])->name('admin.usuarios.toggleEstado');
    });

    // Categorías (Admin)
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
    Route::get('/categorias/{categoria}/toggle-estado', [CategoriaController::class, 'toggleEstado'])->name('categorias.toggleEstado');
    Route::post('/categorias/agregar-producto', [CategoriaController::class, 'agregarProducto'])->name('categorias.agregarProducto');

    // Productos Admin Management
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    Route::get('/productos/{producto}/toggle-estado', [ProductoController::class, 'toggleEstado'])->name('productos.toggleEstado');

    // Ventas Admin Management
    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
    Route::post('/ventas/cambiar-estado', [VentaController::class, 'cambiarEstado'])->name('ventas.cambiarEstado');
    Route::post('/ventas/crear-pos', [VentaController::class, 'crearPOS'])->name('ventas.crearPOS');
    Route::get('/ventas/factura/{id_venta}', [VentaController::class, 'obtenerFactura'])->name('ventas.factura');

    // Reportes (Admin)
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
});

// ── ROLE 2: Trabajador Dashboard & Producción ────────────────
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::prefix('trabajador')->group(function () {
        Route::get('/dashboard', function () {
            return view('trabajador.trabajador');
        })->name('trabajador.dashboard');
    });

    // Producción (solo trabajador)
    Route::get('/produccion', [ProduccionController::class, 'index'])->name('produccion.index');
    Route::post('/produccion', [ProduccionController::class, 'store'])->name('produccion.store');
    Route::get('/produccion/{id}/finalizar', [ProduccionController::class, 'finalizar'])->name('produccion.finalizar');
});

// ── ROLE 1 & 2: Operaciones compartidas ──────────────────────
Route::middleware(['auth', 'role:1,2'])->group(function () {
    // Inventarios
    Route::get('/inventario-mp', [InventarioMateriaPrimaController::class, 'index'])->name('inventario-mp.index');
    Route::get('/inventario-productos', [InventarioProductosController::class, 'index'])->name('inventario-productos.index');

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

// ── ROLE 3: Cliente Routes ───────────────────────────────────
Route::middleware(['auth', 'role:3'])->group(function () {
    Route::prefix('cliente')->group(function () {
        Route::get('/dashboard', function () {
            return view('cliente.cliente');
        })->name('cliente.dashboard');
    });

    // Catálogo y Pedidos de Cliente
    Route::get('/catalogo', [ProductoController::class, 'catalogo'])->name('productos.catalogo');
    Route::get('/mis-compras', [VentaController::class, 'misCompras'])->name('cliente.misCompras');

    // Carrito AJAX Routes
    Route::post('/carrito/agregar', [VentaController::class, 'agregarAlCarrito'])->name('carrito.agregar');
    Route::get('/carrito/obtener', [VentaController::class, 'obtenerCarrito'])->name('carrito.obtener');
    Route::post('/carrito/actualizar', [VentaController::class, 'actualizarCarrito'])->name('carrito.actualizar');
    Route::post('/carrito/eliminar', [VentaController::class, 'eliminarDelCarrito'])->name('carrito.eliminar');
    Route::post('/carrito/finalizar', [VentaController::class, 'finalizarCompra'])->name('carrito.finalizar');
});

require __DIR__.'/auth.php';
