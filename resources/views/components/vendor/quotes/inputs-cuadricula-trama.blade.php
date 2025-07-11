<flux:separator class="my-8" />

<div class="mt-6">
    <h3 class="font-semibold text-sm mb-4 text-gray-900">Ingresa la información de la cuadricula
        trama.</h3>
    <div class="grid grid-cols-2 gap-4 max-w-xs">
        <div>
            <label class="block text-sm font-medium mb-1 text-gray-900">Medida A</label>
            <input type="text" wire:model="medidaACuadriculaTrama"
                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1 text-gray-900">Medida B</label>
            <input type="text" wire:model="medidaBCuadriculaTrama"
                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1 text-gray-900">Distancia Palillaje</label>
            <input type="text" wire:model="distanciaPalillajeCuadriculaTrama"
                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1 text-gray-900">Alto</label>
            <input type="text" wire:model="altoCuadriculaTrama"
                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
        </div>
    </div>
</div>
