<flux:separator class="my-8" />

<div class="mt-6">
    <h3 class="font-semibold text-sm mb-4 text-gray-900">Ingresa la información de la cuadricula.
    </h3>
    <div class="grid grid-cols-2 gap-4 max-w-xs">
        <div>
            <label class="block text-sm font-medium mb-1 text-gray-900">Medida A</label>
            <input type="number" wire:model="medidaACuadricula"
                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
            @error('medidaACuadricula')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1 text-gray-900">Medida B</label>
            <input type="number" wire:model="medidaBCuadricula"
                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
            @error('medidaBCuadricula')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1 text-gray-900">Distancia Palillaje</label>
            <input type="number" wire:model="distanciaPalillajeCuadricula"
                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
            @error('distanciaPalillajeCuadricula')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1 text-gray-900">Alto</label>
            <input type="number" wire:model="altoCuadricula"
                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
            @error('altoCuadricula')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
