@props([
    'logo' => asset('images/tsui.png'),
    'logoDark' => null,
    'alt' => config('app.name', 'Laravel'),
])

<span {{ $attributes->class('inline-flex shrink-0 [:where(&)]:size-10') }}>
    <img
        src="{{ $logo }}"
        alt="{{ $alt }}"
        @class([ 'size-full object-contain' , 'dark:hidden'=> filled($logoDark)])
    />

    @if (filled($logoDark))
    <img
        src="{{ $logoDark }}"
        alt="{{ $alt }}"
        class="hidden size-full object-contain dark:block"
    />
    @endif
</span>
