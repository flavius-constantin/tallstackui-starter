@props([
    'name' => null,
    'href' => '/',
])

<a
    href="{{ $href }}"
    {{ $attributes->class('flex h-10 min-w-0 items-center gap-2 me-4') }}
>
    @if ($slot->isNotEmpty())
    <div class="flex shrink-0 items-center justify-center">
        {{ $slot }}
    </div>
    @endif

    @if ($name)
    <span class="truncate text-sm font-medium text-zinc-800 dark:text-zinc-100">
        {{ $name }}
    </span>
    @endif
</a>
