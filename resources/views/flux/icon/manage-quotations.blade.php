{{-- Credit: Lucide (https://lucide.dev) --}}

@props([
    'variant' => 'outline',
])

@php
    if ($variant === 'solid') {
        throw new \Exception('The "solid" variant is not supported in Lucide.');
    }

    $classes = Flux::classes('shrink-0')->add(
        match ($variant) {
            'outline' => '[:where(&)]:size-6',
            'solid' => '[:where(&)]:size-6',
            'mini' => '[:where(&)]:size-5',
            'micro' => '[:where(&)]:size-4',
        },
    );

    $strokeWidth = match ($variant) {
        'outline' => 2,
        'mini' => 2.25,
        'micro' => 2.5,
    };
@endphp

<svg
    {{ $attributes->class($classes) }}
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 28 28"
    fill="currentColor"
>
    <path d="M3 5.75A2.75 2.75 0 0 1 5.75 3h11.5A2.75 2.75 0 0 1 20 5.75V17h5v4.25A3.75 3.75 0 0 1 21.25 25H6.75A3.75 3.75 0 0 1 3 21.25zM20 23.5h1.25a2.25 2.25 0 0 0 2.25-2.25V18.5H20zM5.75 4.5c-.69 0-1.25.56-1.25 1.25v15.5a2.25 2.25 0 0 0 2.25 2.25H18.5V5.75c0-.69-.56-1.25-1.25-1.25zm2 3.5a.75.75 0 0 0 0 1.5h7.5a.75.75 0 0 0 0-1.5zM7 13.75a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5a.75.75 0 0 1-.75-.75M7.75 18a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5z"/>
</svg>
