@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <x-heading size="xl" level="1">{{ $title }}</x-heading>
    <x-subheading>{{ $description }}</x-subheading>
</div>
