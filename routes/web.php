<?php

use App\Http\Controllers\AdminUsuarioController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\InventarioMateriaPrimaController;
use App\Http\Controllers\InventarioProductosController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
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

// ── Admin Routes ─────────────────────────────────────────────
Route::middleware(['auth', 'role:1'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminUsuarioController::class, 'index'])->name('admin.dashboard');
    Route::post('/usuarios', [AdminUsuarioController::class, 'store'])->name('admin.usuarios.store');
    Route::put('/usuarios/{usuario}', [AdminUsuarioController::class, 'update'])->name('admin.usuarios.update');
    Route::get('/usuarios/{usuario}/toggle-estado', [AdminUsuarioController::class, 'toggleEstado'])->name('admin.usuarios.toggleEstado');
});

// ── Trabajador Routes ────────────────────────────────────────
Route::middleware(['auth', 'role:2'])->prefix('trabajador')->group(function () {
    Route::get('/dashboard', function () {
        return view('trabajador.trabajador');
    })->name('trabajador.dashboard');
});

// ── Cliente Routes ───────────────────────────────────────────
Route::middleware(['auth', 'role:3'])->prefix('cliente')->group(function () {
    Route::get('/dashboard', function () {
        return view('cliente.cliente');
    })->name('cliente.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Categorías Routes ─────────────────────────────────────
    Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::post('/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::put('/categorias/{categoria}', [CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{categoria}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
    Route::get('/categorias/{categoria}/toggle-estado', [CategoriaController::class, 'toggleEstado'])->name('categorias.toggleEstado');
    Route::post('/categorias/agregar-producto', [CategoriaController::class, 'agregarProducto'])->name('categorias.agregarProducto');

    // ── Productos Routes ──────────────────────────────────────
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    Route::get('/productos/{producto}/toggle-estado', [ProductoController::class, 'toggleEstado'])->name('productos.toggleEstado');
    Route::get('/catalogo', [ProductoController::class, 'catalogo'])->name('productos.catalogo');

    // ── Lotes Routes ──────────────────────────────────────────
    Route::get('/lotes', [LoteController::class, 'index'])->name('lotes.index');
    Route::post('/lotes', [LoteController::class, 'store'])->name('lotes.store');
    Route::put('/lotes/{lote}', [LoteController::class, 'update'])->name('lotes.update');
    Route::delete('/lotes/{lote}', [LoteController::class, 'destroy'])->name('lotes.destroy');

    // Batch details – separate resource under the same auth group
    Route::get('/lotes/{lote}/detalles', [LoteController::class, 'detalles'])->name('lotes.detalles');
    Route::post('/lotes/detalles', [LoteController::class, 'storeDetalle'])->name('lotes.detalles.store');
    Route::put('/lotes/detalles/{detalle}', [LoteController::class, 'updateDetalle'])->name('lotes.detalles.update');

    // ── Inventario Materia Prima Routes ──────────────────────────────────
    Route::get('/inventario-mp', [InventarioMateriaPrimaController::class, 'index'])->name('inventario-mp.index');

    // ── Inventario Productos Routes ───────────────────────────────────
    Route::get('/inventario-productos', [InventarioProductosController::class, 'index'])->name('inventario-productos.index');

    // ── Devoluciones Routes ───────────────────────────────────────────
    Route::get('/devoluciones', [DevolucionController::class, 'index'])->name('devoluciones.index');
    Route::post('/devoluciones', [DevolucionController::class, 'store'])->name('devoluciones.store');

    // ── Carrito Routes (AJAX, sesión) ──────────────────────────────────
    Route::get('/carrito', [CarritoController::class, 'obtener'])->name('carrito.obtener');
    Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::post('/carrito/actualizar', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
    Route::post('/carrito/eliminar', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

    // ── Ventas Routes (cliente) ───────────────────────────────────────
    Route::get('/mis-compras', [VentaController::class, 'misCompras'])->name('ventas.mis-compras');
    Route::get('/checkout', [VentaController::class, 'checkout'])->name('ventas.checkout');
    Route::post('/checkout/confirmar', [VentaController::class, 'confirmarCompra'])->name('ventas.confirmar');

    // ── Ventas Routes (admin) ─────────────────────────────────────────
    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
    Route::get('/ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show');
    Route::get('/ventas/{venta}/estado/{estado}', [VentaController::class, 'cambiarEstado'])->name('ventas.estado');

    // ── Producción Routes ─────────────────────────────────────
    Route::get('/produccion', [ProduccionController::class, 'index'])->name('produccion.index');
    Route::post('/produccion', [ProduccionController::class, 'store'])->name('produccion.store');
    Route::get('/produccion/{id}/finalizar', [ProduccionController::class, 'finalizar'])->name('produccion.finalizar');

    // ── Ventas Admin Routes ─────────────────────────────
    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
    Route::post('/ventas/cambiar-estado', [VentaController::class, 'cambiarEstado'])->name('ventas.cambiarEstado');
    Route::post('/ventas/crear-pos', [VentaController::class, 'crearPOS'])->name('ventas.crearPOS');
    Route::get('/ventas/factura/{id_venta}', [VentaController::class, 'obtenerFactura'])->name('ventas.factura');

    // ── Carrito AJAX Routes (all authenticated, role checked in controller) ──
    Route::post('/carrito/agregar', [VentaController::class, 'agregarAlCarrito'])->name('carrito.agregar');
    Route::get('/carrito/obtener', [VentaController::class, 'obtenerCarrito'])->name('carrito.obtener');
    Route::post('/carrito/actualizar', [VentaController::class, 'actualizarCarrito'])->name('carrito.actualizar');
    Route::post('/carrito/eliminar', [VentaController::class, 'eliminarDelCarrito'])->name('carrito.eliminar');
    Route::post('/carrito/finalizar', [VentaController::class, 'finalizarCompra'])->name('carrito.finalizar');

    // ── Cliente – Mis Compras ──────────────────────────────
    Route::get('/mis-compras', [VentaController::class, 'misCompras'])->name('cliente.misCompras');
});

require __DIR__.'/auth.php';
