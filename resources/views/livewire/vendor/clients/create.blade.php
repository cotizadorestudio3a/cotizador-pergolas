<div>

    <div class="flex items-center gap-4">
        <flux:modal.trigger name="create">
            <flux:button variant="primary">Crear nuevo cliente</flux:button>
        </flux:modal.trigger>

    </div>
    <flux:modal name="create" class="md:w-96" variant="flyout">

        <form wire:submit="save" class="space-y-6"> {{-- Aquí va el evento Livewire --}}
            <div>
                <flux:heading size="lg">Crear cliente</flux:heading>
                <flux:text class="mt-2">Ingresa los datos del cliente.</flux:text>
            </div>

            <flux:input label="Nombre" wire:model="name" placeholder="Ej. Juan Pérez" />
            <flux:input label="DNI" wire:model="dni" type="number" placeholder="12345678" />
            <flux:input label="Telefono" wire:model="phone" type="number" placeholder="12345678" />
            <flux:select label="Provincia" wire:model="province" placeholder="Seleccione una provincia">
                @foreach ($provinces as $prov)
                    <flux:select.option value="{{ $prov }}">{{ $prov }}</flux:select.option>
                @endforeach
            </flux:select>



            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">Registrar cliente</flux:button>
            </div>
        </form>
    </flux:modal>


</div>
