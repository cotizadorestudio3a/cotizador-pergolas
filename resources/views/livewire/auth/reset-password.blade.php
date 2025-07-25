<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Reset password')" :description="__('Por favor, ingresa tu nueva contraseña a continuación')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="resetPassword" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email')"
            type="email"
            required
            autocomplete="email"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Nueva contraseña')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Nueva contraseña')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirmar nueva contraseña')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirmar nueva contraseña')"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Restablecer contraseña') }}
            </flux:button>
        </div>
    </form>
</div>
