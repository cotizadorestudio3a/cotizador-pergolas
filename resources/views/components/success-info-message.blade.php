@props([
    'on',
])

<div
    x-data="{ shown: false, timeout: null }"
    x-init="@this.on('{{ $on }}', (e) => {
    message = e; //parametro enviado desde la clase
    clearTimeout(timeout);
    shown = true;
    timeout = setTimeout(() => {
    shown = false }, 6000); })"
    x-show.transition.out.opacity.duration.1500ms="shown"
    x-transition:leave.opacity.duration.1500ms
    style="display: none"
    {{ $attributes->merge(['class' => 'text-sm text-green-500']) }}
>
    {{ $slot->isEmpty() ? __('Guardado.') : $slot }}
</div>
