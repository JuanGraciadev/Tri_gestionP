@props([
    'icon' => 'fas fa-inbox',
    'title' => 'Sin registros disponibles',
    'message' => 'No hay información para mostrar en este momento.',
    'actionLabel' => null,
    'actionUrl' => null,
    'actionIcon' => null,
])

<div {{ $attributes->merge(['class' => 'px-8 py-20 text-center']) }}>
    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
        <i class="{{ $icon }} text-3xl"></i>
    </div>
    <p class="text-slate-600 font-bold text-lg mb-1">{{ $title }}</p>
    @if ($message)
        <p class="text-slate-400 font-medium text-sm max-w-md mx-auto mb-5">{{ $message }}</p>
    @endif
    @if ($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-sky-500 to-indigo-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-sky-500/20 hover:shadow-xl hover:-translate-y-0.5 transition-all cursor-pointer">
            @if ($actionIcon)
                <i class="{{ $actionIcon }}"></i>
            @endif
            <span>{{ $actionLabel }}</span>
        </a>
    @endif
</div>

