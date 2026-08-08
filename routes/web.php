<?php

use App\Http\Controllers\ProfileController;
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
    Route::get('/dashboard', function () {
        return view('admin.admin');
    })->name('admin.dashboard');
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
});

require __DIR__.'/auth.php';
