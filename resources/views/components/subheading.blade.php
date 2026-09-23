@props([
    'size' => null,
])

@php
$classes = [
    '[:where(&)]:text-zinc-500 [:where(&)]:dark:text-white/70',
    match ($size) {
        'lg' => 'text-lg',
        'base' => 'text-base',
        'xs' => 'text-xs',
        default => 'text-sm',
    },
];
@endphp

<div {{ $attributes->class($classes) }} data-subheading>
    {{ $slot }}
</div>
