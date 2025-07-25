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
    viewBox="0 0 24 24"
    fill="currentColor"
>
    <path d="M4 5.25A2.25 2.25 0 0 1 6.25 3h9.5A2.25 2.25 0 0 1 18 5.25V14h4v3.75A3.25 3.25 0 0 1 18.75 21h-6.772c.297-.463.536-.966.709-1.5H16.5V5.25a.75.75 0 0 0-.75-.75h-9.5a.75.75 0 0 0-.75.75v5.826a6.5 6.5 0 0 0-1.5.422zm9.75 7.25h-3.096a6.5 6.5 0 0 0-2.833-1.366A.75.75 0 0 1 8.25 11h5.5a.75.75 0 0 1 0 1.5m4.25 7h.75a1.75 1.75 0 0 0 1.75-1.75V15.5H18zM8.25 7a.75.75 0 0 0 0 1.5h5.5a.75.75 0 0 0 0-1.5zM12 17.5a5.5 5.5 0 1 0-11 0a5.5 5.5 0 0 0 11 0M7 18l.001 2.503a.5.5 0 1 1-1 0V18H3.496a.5.5 0 0 1 0-1H6v-2.5a.5.5 0 1 1 1 0V17h2.497a.5.5 0 0 1 0 1z"/>
</svg>

