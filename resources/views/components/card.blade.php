@props(['class' => ''])
<div {{ $attributes->merge(['class' => 'glass-card rounded-[2.5rem] border border-white overflow-hidden shadow-sm ' . $class]) }}>
    {{ $slot }}
</div>

