{{-- Backdrop overlay para móviles --}}
<div x-show="mobileSidebar"
     @click="mobileSidebar = false"
     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display:none"></div>

@php $rol = (int) (Auth::user()->id_rol ?? 0); @endphp

@if ($rol === 1)
    @include('layouts.partials.sidebar.admin')
@elseif ($rol === 2)
    @include('layouts.partials.sidebar.trabajador')
@elseif ($rol === 3)
    @include('layouts.partials.sidebar.cliente')
@else
    @include('layouts.partials.sidebar.cliente')
@endif

