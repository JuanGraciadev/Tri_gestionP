@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
    'icon' => null,
    'size' => 'md',
])

@php
    $sizes = [
        'sm' => 'px-3.5 py-2 text-xs rounded-xl gap-1.5',
        'md' => 'px-5 py-2.5 text-sm rounded-2xl gap-2',
        'lg' => 'px-7 py-3.5 text-base rounded-2xl gap-2.5',
    ];
    $variants = [
        'primary'   => 'bg-gradient-to-r from-sky-500 to-indigo-600 hover:from-sky-600 hover:to-indigo-700 text-white shadow-md shadow-sky-500/20 hover:shadow-lg',
        'emerald'   => 'bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white shadow-md shadow-emerald-500/20 hover:shadow-lg',
        'secondary' => 'bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-sm hover:border-slate-300',
        'danger'    => 'bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white shadow-md shadow-rose-500/20 hover:shadow-lg',
        'ghost'     => 'bg-transparent hover:bg-slate-100 text-slate-600 hover:text-slate-900',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $varClass = $variants[$variant] ?? $variants['primary'];
    $baseClasses = "inline-flex items-center justify-center font-bold transition-all transform active:scale-95 cursor-pointer select-none {$sizeClass} {$varClass}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        @if ($icon) <i class="{{ $icon }}"></i> @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClasses]) }}>
        @if ($icon) <i class="{{ $icon }}"></i> @endif
        <span>{{ $slot }}</span>
    </button>
@endif

