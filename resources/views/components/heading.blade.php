@props([
    'size' => 'sm',
    'level' => null,
])

@php
$tag = in_array((int) $level, [1, 2, 3, 4, 5, 6], true)
    ? 'h' . (int) $level
    : 'div';

$classes = [
    'font-medium',
    '[:where(&)]:text-zinc-800 [:where(&)]:dark:text-white',
    match ($size) {
        '4xl' => 'text-4xl',
        '2xl' => 'text-2xl',
        'lg' => 'text-lg',
        'base' => 'text-base',
        'xs' => 'text-xs',
        default => 'text-sm',
    },
    '[&:has(+[data-subheading])]:mb-2',
    '[[data-subheading]+&]:mt-2',
];
@endphp

<{{ $tag }} {{ $attributes->class($classes) }}>
    {{ $slot }}
</{{ $tag }}>
