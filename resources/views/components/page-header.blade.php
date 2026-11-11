@props(['tag' => null, 'title', 'highlight' => null, 'subtitle' => null, 'color' => 'sky'])

@php
    $gradients = [
        'sky' => 'from-sky-500 to-indigo-600',
        'emerald' => 'from-emerald-500 to-teal-600',
        'amber' => 'from-amber-500 to-orange-600',
        'indigo' => 'from-indigo-500 to-purple-600',
        'rose' => 'from-rose-500 to-pink-600',
    ];
    $dotColors = [
        'sky' => 'bg-sky-500',
        'emerald' => 'bg-emerald-500',
        'amber' => 'bg-amber-500',
        'indigo' => 'bg-indigo-500',
        'rose' => 'bg-rose-500',
    ];
    $gradient = $gradients[$color] ?? $gradients['sky'];
    $dot = $dotColors[$color] ?? $dotColors['sky'];
@endphp

<div class="glass-card rounded-[2.5rem] border border-white overflow-hidden shadow-sm">
    <div class="p-8 md:p-10 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-r from-slate-50 to-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 {{ $dot }}/5 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
        <div class="relative z-10">
            @if ($tag)
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white border border-slate-200 shadow-sm mb-3">
                    <span class="w-2 h-2 rounded-full {{ $dot }} animate-pulse"></span>
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">{{ $tag }}</span>
                </div>
            @endif
            <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-800 tracking-tight">
                {{ $title }}
                @if ($highlight)
                    <span class="text-transparent bg-clip-text bg-gradient-to-r {{ $gradient }}">{{ $highlight }}</span>
                @endif
            </h2>
            @if ($subtitle)
                <p class="text-slate-500 mt-2 font-medium">{{ $subtitle }}</p>
            @endif
        </div>
        @if (isset($action))
            <div class="relative z-10 flex items-center gap-3">
                {{ $action }}
            </div>
        @endif
    </div>

    @if (isset($stats))
        <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 bg-white/60">
            {{ $stats }}
        </div>
    @endif
</div>

