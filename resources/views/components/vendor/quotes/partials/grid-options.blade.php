{{-- Opciones de cuadrícula --}}
<div class="pt-4 border-t border-gray-200 animate-fade-in">
    <flux:radio.group wire:model="selectedCuadricula" class="space-y-2" label="Opciones de cuadrícula">
        @if (!$isRestrictedVariant)
            {{-- Opciones completas para variantes normales --}}
            <div class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50 transition-colors">
                <flux:radio 
                    value="cuadricula"
                    label="Cuadricula"
                    description="Vista estándar con líneas de guía"
                    checked
                />
            </div>

            <div class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50 transition-colors">
                <flux:radio 
                    value="cuadricula_trama"
                    label="Cuadrícula con trama"
                    description="Incluye patrones y texturas"
                />
            </div>

            <div class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50 transition-colors">
                <flux:radio 
                    value="sin_cuadricula"
                    label="Sin cuadrícula"
                    description="Vista limpia sin líneas"
                />
            </div>
        @else
            {{-- Solo opción sin cuadrícula para variantes restringidas (IDs 2 y 11) --}}
            <div class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50 transition-colors">
                <flux:radio 
                    value="sin_cuadricula"
                    label="Sin cuadrícula"
                    description="Vista limpia sin líneas"
                    checked
                />
            </div>
        @endif
    </flux:radio.group>
</div>

