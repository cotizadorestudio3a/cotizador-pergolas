<div>

    <div class="flex items-center gap-4">
        <flux:modal.trigger name="create">
            <flux:button variant="primary">Crear nuevo vendedor</flux:button>
        </flux:modal.trigger>

        <x-success-info-message class="me-3 text-green-500" on="vendor-created">
            Vendedor <strong x-text="params"></strong> agregado correctamente.
        </x-success-info-message>


    </div>
    <flux:modal name="create" class="md:w-96" variant="flyout">
        <form wire:submit="save" class="space-y-6"> {{-- Aquí va el evento Livewire --}}
            <div>
                <flux:heading size="lg">Crear vendedor</flux:heading>
                <flux:text class="mt-2">Ingresa los datos del vendedor.</flux:text>
            </div>

            <flux:input label="Nombre" wire:model="name" placeholder="Ej. Juan Pérez"/>
            <flux:input label="Correo" wire:model="email" type="email" placeholder="correo@ejemplo.com"/>

            <div class="flex">
                <flux:spacer/>
                <flux:button type="submit" variant="primary">Registrar vendedor</flux:button>
            </div>
        </form>
    </flux:modal>


</div>
