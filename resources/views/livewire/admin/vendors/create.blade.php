<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Crear un vendedor')" :description="__('Ingresa los detalles abajo para crear una cuenta.')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6" novalidate>
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Nombre')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Ingresa el nombre completo')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Correo electronico')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@ejemplo.com"
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Crear vendedor') }}
            </flux:button>
        </div>
    </form>
</div>
