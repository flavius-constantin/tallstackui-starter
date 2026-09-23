@props([
    'vertical' => false,
    'variant' => null,
    'text' => null,
])

@php
$color = match ($variant) {
    'subtle' => 'bg-zinc-800/5 dark:bg-white/10',
    default => 'bg-zinc-800/15 dark:bg-white/20',
};

$classes = [
    'border-0 [print-color-adjust:exact]',
    $color,
];
@endphp

@if (! $vertical && filled($text))
<div {{ $attributes->class(['flex w-full items-center gap-6']) }}>
    <div
        @class([...$classes, 'h-px min-w-0 flex-1' ])
        aria-hidden="true"></div>

    <span class="shrink-0 whitespace-nowrap text-sm font-medium text-zinc-500 dark:text-zinc-300">
        {{ $text }}
    </span>

    <div
        @class([...$classes, 'h-px min-w-0 flex-1' ])
        aria-hidden="true"></div>
</div>
@else
<div
    {{ $attributes->class([
            ...$classes,
            'shrink-0',
            $vertical ? 'w-px self-stretch' : 'h-px w-full',
        ]) }}
    role="separator"
    aria-orientation="{{ $vertical ? 'vertical' : 'horizontal' }}"></div>
@endif
