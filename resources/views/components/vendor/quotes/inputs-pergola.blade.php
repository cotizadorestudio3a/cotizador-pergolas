@props([
    "index",
    "selectorColorVisible" => false,
    "servicioSelectorColor" => null,
    "indiceSelectorColor" => null,
    "selectedColor" => null,
    "inputsPorServicio" => []
])

<div class="mb-6">
    <div>
        <h3 class="font-semibold text-sm mb-4 text-gray-900">Ingresa la información de la pérgola.</h3>
        <div class="grid grid-cols-2 gap-4 max-w-xs">
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-900">Medida A</label>
                <input type="number" wire:model="inputsPorServicio.{{ $index }}.medidaA"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
                @error('inputsPorServicio.{{ $index }}.medidaA')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-900">Medida B</label>
                <input type="number" wire:model="inputsPorServicio.{{ $index }}.medidaB"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
                @error('inputsPorServicio.{{ $index }}.medidaB')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-900">Alto</label>
                <input type="number" wire:model="inputsPorServicio.{{ $index }}.alto"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
                @error('inputsPorServicio.{{ $index }}.alto')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-900">Columnas</label>
                <div class="flex gap-2">
                    <input type="number" wire:model.live="inputsPorServicio.{{ $index }}.n_columnas"
                        class="flex-1 px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
                    
                    <!-- Indicador de colores de las columnas -->
                    @if(isset($inputsPorServicio[$index]['n_columnas']) && $inputsPorServicio[$index]['n_columnas'] > 0)
                        <div class="flex gap-1 items-center">
                            @for($i = 0; $i < min((int)$inputsPorServicio[$index]['n_columnas'], 10); $i++)
                                @php
                                    $columnaColor = $inputsPorServicio[$index]['colores_columnas'][$i] ?? $selectedColor ?? 'azul';
                                    $colorClass = match($columnaColor) {
                                        'azul' => 'bg-blue-500',
                                        'negro' => 'bg-black',
                                        'blanco' => 'bg-white border-2 border-gray-300',
                                        'gris' => 'bg-gray-400',
                                        'rojo' => 'bg-red-500',
                                        default => 'bg-blue-500'
                                    };
                                @endphp
                                <button type="button" 
                                    wire:click="abrirSelectorColorColumna({{ $index }}, {{ $i }})"
                                    class="w-6 h-6 rounded-full {{ $colorClass }} hover:scale-110 transition-transform cursor-pointer relative group border"
                                    title="Columna {{ $i + 1 }} - Clic para cambiar color">
                                    <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                        Columna {{ $i + 1 }}
                                    </span>
                                </button>
                            @endfor
                            @if((int)($inputsPorServicio[$index]['n_columnas'] ?? 0) > 10)
                                <span class="text-xs text-gray-500">+{{ (int)$inputsPorServicio[$index]['n_columnas'] - 10 }}</span>
                            @endif
                        </div>
                    @else
                        <div class="text-xs text-gray-400">Ingrese número de columnas para ver selectores de color</div>
                    @endif
                </div>
                @error('inputsPorServicio.{{ $index }}.n_columnas')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-900">Bajantes</label>
                <input type="number" wire:model="inputsPorServicio.{{ $index }}.n_bajantes"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
                @error('inputsPorServicio.{{ $index }}.n_bajantes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-900">Anillos</label>
                <input type="number" wire:model="inputsPorServicio.{{ $index }}.anillos"
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200">
                @error('inputsPorServicio.{{ $index }}.anillos')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</div>

<!-- Modal para seleccionar color de columna -->
@if($selectorColorVisible ?? false)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="cerrarSelectorColorColumna">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4" wire:click.stop>
            <h3 class="text-lg font-semibold mb-4 text-gray-900">
                Seleccionar color para Columna {{ ($indiceSelectorColor ?? 0) + 1 }}
            </h3>
            
            <div class="space-y-3">
                <button type="button" 
                    wire:click="cambiarColorColumna('azul')"
                    class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors border">
                    <div class="w-6 h-6 bg-blue-500 rounded-full"></div>
                    <span class="text-gray-700 font-medium">Azul</span>
                </button>
                
                <button type="button" 
                    wire:click="cambiarColorColumna('negro')"
                    class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors border">
                    <div class="w-6 h-6 bg-black rounded-full"></div>
                    <span class="text-gray-700 font-medium">Negro</span>
                </button>
                
                <button type="button" 
                    wire:click="cambiarColorColumna('blanco')"
                    class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors border">
                    <div class="w-6 h-6 bg-white border-2 border-gray-300 rounded-full"></div>
                    <span class="text-gray-700 font-medium">Blanco</span>
                </button>
                
                <button type="button" 
                    wire:click="cambiarColorColumna('gris')"
                    class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors border">
                    <div class="w-6 h-6 bg-gray-400 rounded-full"></div>
                    <span class="text-gray-700 font-medium">Gris</span>
                </button>
                
                <button type="button" 
                    wire:click="cambiarColorColumna('rojo')"
                    class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors border">
                    <div class="w-6 h-6 bg-red-500 rounded-full"></div>
                    <span class="text-gray-700 font-medium">Rojo</span>
                </button>
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" 
                    wire:click="cerrarSelectorColorColumna"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
@endif
