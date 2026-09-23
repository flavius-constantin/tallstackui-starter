@props([
'href',
'icon' => null,
'current' => false,
])

<a
    href="{{ $href }}"
    @if ($current) aria-current="page" @endif
    {{ $attributes->class([
        'flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors',
        'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-500',
        'bg-primary-50 text-primary-700 dark:bg-primary-500/15 dark:text-primary-300' => $current,
        'text-zinc-600 hover:bg-primary-50 hover:text-primary-700 dark:text-zinc-400 dark:hover:bg-primary-500/10 dark:hover:text-primary-300' => ! $current,
    ]) }}>
    @if ($icon)
    <x-icon :name="$icon" class="size-5 shrink-0" />
    @endif

    <span>{{ $slot }}</span>
</a>
