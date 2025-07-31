@props([
    'on',
])

<div
    x-data="{ shown: false, timeout: null, error: '' }"
    x-init="@this.on('{{ $on }}', (e) => {
        // Manejar diferentes tipos de eventos
        if (typeof e === 'string') {
            error = e;
        } else if (e && e.message) {
            error = e.message;
        } else if (e && typeof e === 'object') {
            error = e.toString();
        } else {
            error = 'Ha ocurrido un error';
        }
        
        clearTimeout(timeout);
        shown = true;
        timeout = setTimeout(() => {
            shown = false;
        }, 6000);
    })"
    x-show.transition.out.opacity.duration.1500ms="shown"
    x-transition:leave.opacity.duration.1500ms
    style="display: none"
    {{ $attributes->merge(['class' => 'text-sm']) }}
>
    {{ $slot->isEmpty() ? __('Error.') : $slot }}
</div>
