@props(['estado', 'tipo' => 'general'])

@php
    $norm = strtolower(trim((string)$estado));
    
    // Configuración visual por estado
    $config = match(true) {
        // Exitosos / Activos
        in_array($norm, ['1', 'activo', 'activa', 'entregado', 'finalizada', 'confirmado', 'completado']) => [
            'bg' => 'bg-emerald-50',
            'border' => 'border-emerald-200',
            'text' => 'text-emerald-700',
            'dot' => 'bg-emerald-500',
            'icon' => 'fa-check-circle',
            'label' => in_array($norm, ['1', 'activo', 'activa']) ? 'Activo' : ucfirst($estado)
        ],
        // En proceso / En producción / Pendiente de revisión
        in_array($norm, ['en proceso', 'en producción', 'en produccion']) => [
            'bg' => 'bg-sky-50',
            'border' => 'border-sky-200',
            'text' => 'text-sky-700',
            'dot' => 'bg-sky-500',
            'icon' => 'fa-spinner fa-spin',
            'label' => ucfirst($estado)
        ],
        // Pendiente / En espera
        in_array($norm, ['pendiente', 'espera']) => [
            'bg' => 'bg-amber-50',
            'border' => 'border-amber-200',
            'text' => 'text-amber-700',
            'dot' => 'bg-amber-500',
            'icon' => 'fa-clock',
            'label' => 'Pendiente'
        ],
        // Cancelado / Inactivo / Rechazado
        in_array($norm, ['0', 'inactivo', 'inactiva', 'cancelado', 'cancelada', 'rechazado', 'rechazada']) => [
            'bg' => 'bg-rose-50',
            'border' => 'border-rose-200',
            'text' => 'text-rose-700',
            'dot' => 'bg-rose-500',
            'icon' => 'fa-circle-xmark',
            'label' => in_array($norm, ['0', 'inactivo', 'inactiva']) ? 'Inactivo' : ucfirst($estado)
        ],
        default => [
            'bg' => 'bg-slate-50',
            'border' => 'border-slate-200',
            'text' => 'text-slate-700',
            'dot' => 'bg-slate-400',
            'icon' => 'fa-circle-info',
            'label' => ucfirst($estado)
        ]
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border text-xs font-black {$config['bg']} {$config['border']} {$config['text']}"]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
    <span>{{ $config['label'] }}</span>
</span>

