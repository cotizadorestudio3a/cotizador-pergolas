<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Actualizar contraseña')" :subheading="__('Asegúrese de que su cuenta utilice una contraseña larga y aleatoria para mantener la seguridad')">
        <form wire:submit="updatePassword" class="mt-6 space-y-6">
            <flux:input
                wire:model="current_password"
                :label="__('Contraseña actual')"
                type="password"
                required
                autocomplete="current-password"
            />
            <flux:input
                wire:model="password"
                :label="__('Nueva contraseña')"
                type="password"
                required
                autocomplete="new-password"
            />
            <flux:input
                wire:model="password_confirmation"
                :label="__('Vuelve a escribir tu nueva contraseña')"
                type="password"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Cambiar contraseña') }}</flux:button>
                </div>

                <x-success-info-message class="me-3" on="password-updated">
                    {{ __('Se actulizó tu contraseña.') }}
                </x-success-info-message>
            </div>
        </form>
    </x-settings.layout>
</section>
