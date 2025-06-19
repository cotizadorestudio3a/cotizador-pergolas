@props(['disabled' => false])

<button {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150',
    'style' => 'background-color: #292D32;'
]) !!}>
    {{ $slot }}
</button>
