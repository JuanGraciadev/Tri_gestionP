@php $rol = (int) (Auth::user()->id_rol ?? 0); @endphp
@if ($rol === 1)
    @include('partials.sidebar-admin')
@elseif ($rol === 2)
    @include('partials.sidebar-trabajador')
@elseif ($rol === 3)
    @include('partials.sidebar-cliente')
@else
    @include('partials.sidebar-cliente')
@endif

