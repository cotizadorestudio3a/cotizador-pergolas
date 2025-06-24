<div>
    <flux:modal.trigger :name="'delete-vendor-'.$user->id">
        <flux:button variant="danger">Eliminar</flux:button>
    </flux:modal.trigger>

    <flux:modal :name="'delete-vendor-'.$user->id" class="min-w-[22rem]">

        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Quieres eliminar este vendedor?</flux:heading>

                <flux:text class="mt-2">
                    <p>Estas a punto de eliminar este usuario.</p>
                    <p>Esta accion no se puede deshacer.</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer/>

                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="danger"
                             wire:click="delete({{ $user->id }})">Sí, eliminar vendedor
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
