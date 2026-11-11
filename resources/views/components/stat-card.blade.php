@props(['label', 'value', 'icon' => null, 'color' => 'sky', 'description' => null])

@php
    $textColors = [
        'sky' => 'text-sky-500',
        'emerald' => 'text-emerald-500',
        'amber' => 'text-amber-500',
        'indigo' => 'text-indigo-500',
        'rose' => 'text-rose-500',
        'slate' => 'text-slate-700',
    ];
    $bgColors = [
        'sky' => 'bg-sky-50 text-sky-600 border-sky-100',
        'emerald' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'amber' => 'bg-amber-50 text-amber-600 border-amber-100',
        'indigo' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
        'rose' => 'bg-rose-50 text-rose-600 border-rose-100',
        'slate' => 'bg-slate-100 text-slate-600 border-slate-200',
    ];
    $textColor = $textColors[$color] ?? $textColors['sky'];
    $bgIcon = $bgColors[$color] ?? $bgColors['sky'];
@endphp

@if ($icon)
    {{-- Dashboard card style --}}
    <div {{ $attributes->merge(['class' => 'glass-card p-6 rounded-3xl border border-white/60 relative overflow-hidden group hover:shadow-md transition-all']) }}>
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider truncate">{{ $label }}</p>
                <h3 class="text-2xl sm:text-3xl font-black text-slate-800 mt-1 truncate">{{ $value }}</h3>
                @if ($description)
                    <p class="text-xs text-slate-400 font-medium mt-1">{{ $description }}</p>
                @endif
            </div>
            <div class="w-12 h-12 rounded-2xl {{ $bgIcon }} border flex items-center justify-center text-xl shrink-0 group-hover:scale-110 transition-transform">
                <i class="{{ $icon }}"></i>
            </div>
        </div>
    </div>
@else
    {{-- Banner header stat style --}}
    <div {{ $attributes->merge(['class' => 'p-6 text-center']) }}>
        <div class="text-3xl font-black {{ $textColor }}">{{ $value }}</div>
        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">{{ $label }}</div>
        @if ($description)
            <div class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $description }}</div>
        @endif
    </div>
@endif

