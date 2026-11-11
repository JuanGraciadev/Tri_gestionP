@extends('layouts.panel')

@section('title', 'Dashboard')
@section('subtitle', 'Panel de usuario autenticado')

@section('content')
<div class="glass-card rounded-[2rem] p-8 border border-white/80 shadow-sm">
    <h2 class="text-xl font-bold text-slate-800 mb-2">¡Bienvenido!</h2>
    <p class="text-slate-600 text-sm">Has iniciado sesión correctamente en el sistema TRIGESTION.</p>
</div>
@endsection
