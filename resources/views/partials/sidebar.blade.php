@php $rol = (int) (Auth::user()->id_rol ?? 0); @endphp
@if ($rol === 1)
    @include('partials.sidebar-admin')
@elseif ($rol === 2)
    @include('partials.sidebar-trabajador')
@else
    @include('partials.sidebar-admin')
@endif
