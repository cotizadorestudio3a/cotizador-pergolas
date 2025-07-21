@props([
    "index"
])
<flux:separator class="my-8" />

<div class="mt-6">
    <h3 class="font-semibold text-sm mb-4 text-gray-900">Ingresa la información de la cuadricula
        trama.</h3>
    <div class="grid grid-cols-2 gap-4 max-w-xs">
        <div>
            <label class="block text-sm font-medium mb-1 text-gray-900">Medida A Cuadrícula</label>
            <input type="number" wire:model="inputsPorServicio.{{ $index }}.medidaACuadricula"
                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
            @error('inputsPorServicio.{{ $index }}.medidaACuadricula')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1 text-gray-900">Medida B Cuadrícula</label>
            <input type="number" wire:model="inputsPorServicio.{{ $index }}.medidaBCuadricula"
                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
            @error('inputsPorServicio.{{ $index }}.medidaBCuadricula')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1 text-gray-900">Distancia Palillaje</label>
            <input type="number" wire:model="inputsPorServicio.{{ $index }}.distanciaPalillaje"
                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
            @error('inputsPorServicio.{{ $index }}.distanciaPalillaje')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-sm font-medium mb-1 text-gray-900">Alto Cuadrícula</label>
            <input type="number" wire:model="inputsPorServicio.{{ $index }}.altoCuadricula"
                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
            @error('inputsPorServicio.{{ $index }}.altoCuadricula')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
