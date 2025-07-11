<div class="mb-6">
    <div>
        <h3 class="font-semibold text-sm mb-4 text-gray-900">Ingresa la información de la pérgola.</h3>
        <div class="grid grid-cols-2 gap-4 max-w-xs">
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-900">Medida A</label>
                <input type="number" wire:model="medidaA"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
                @error('medidaA')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-900">Medida B</label>
                <input type="number" wire:model="medidaB"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
                @error('medidaB')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-900">Alto</label>
                <input type="number" wire:model="alto"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
                @error('alto')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-900">Columnas</label>
                <input type="number" wire:model="n_columnas"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
                @error('n_columnas')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-900">Bajantes</label>
                <input type="number" wire:model="n_bajantes"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
                @error('n_bajantes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-900">Anillos</label>
                <input type="number" wire:model="anillos"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
                @error('anillos')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>
