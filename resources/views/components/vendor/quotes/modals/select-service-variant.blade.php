<div class="space-y-4 max-h-[70vh] overflow-y-auto">
    <!-- Header más compacto -->
    <div class="space-y-1">
        <h2 class="text-lg font-semibold text-gray-900">Selecciona tu servicio</h2>
        <p class="text-sm text-gray-600">Elige el servicio que mejor se adapte a tus necesidades</p>
    </div>

    <!-- Grid de variantes más compacto -->
    <div class="grid grid-cols-2 gap-3">
        @foreach ($variants as $variant)
            <div class="group relative overflow-hidden rounded-lg border transition-all duration-300 ease-in-out
                {{ $selectedVariant === $variant->id
                    ? 'border-primary/30 bg-primary/5 shadow-sm'
                    : 'border-gray-200 bg-white hover:border-gray-300 hover:shadow-sm' }}"
                wire:click="$set('selectedVariant', {{ $variant->id }})">

                <!-- Indicador de selección más pequeño -->
                @if ($selectedVariant == $variant->id)
                    <div
                        class="absolute top-3 right-3 w-4 h-4 bg-primary rounded-full flex items-center justify-center shadow-sm">
                        <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="sr-only">Seleccionado</span>
                    </div>
                @endif

                <!-- Contenido principal más compacto -->
                <div class="p-4 cursor-pointer">
                    <!-- Encabezado del servicio -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-900 group-hover:text-primary transition-colors">
                                {{ $variant->service->name }}
                            </h3>
                            <span
                                class="inline-block mt-1 px-2 py-0.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-full">
                                {{ $variant->name }}
                            </span>
                        </div>
                    </div>

                    <!-- Opciones expandidas -->
                    <div
                        class="overflow-hidden transition-all duration-300 ease-in-out
                        {{ $selectedVariant === $variant->id ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}">

                        @if ($selectedVariant === $variant->id)
                            <div class="pt-3 border-t border-gray-200 animate-fade-in">
                                <div class="space-y-2">
                                    <flux:radio.group wire:model="selectedCuadricula" class="space-y-1" label="Opciones de cuadrícula">
                                        <div class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50 transition-colors">
                                            <flux:radio 
                                            value="cuadricula" 
                                            label="Cuadricula"
                                            description="Vista estándar con líneas de guía"
                                            checked 
                                            />
                                        </div>

                                        <div
                                            class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50 transition-colors">
                                            <flux:radio 
                                            value="cuadricula_trama" 
                                            label="Cuadrícula con trama"
                                            description="Incluye patrones y texturas"
                                            />
                                        </div>

                                        <div
                                            class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50 transition-colors">
                                            <flux:radio value="sin_cuadricula" label="Sin cuadrícula" description="Vista limpia sin líneas" />
                                        </div>
                                    </flux:radio.group>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Botón de continuar mejorado y fijo -->
    <div class="sticky bottom-0 bg-white border-t border-gray-200 p-4 mt-4 -mx-4 -mb-4">
        <div class="flex justify-between items-center">
            <div class="text-xs text-gray-600">
                @if ($selectedVariant)
                    <span class="flex items-center">
                        <svg class="w-3 h-3 text-green-500 mr-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Servicio seleccionado
                    </span>
                @else
                    <span class="text-gray-400">Selecciona un servicio para continuar</span>
                @endif
            </div>        
        </div>
    </div>
</div>

<!-- Estilos adicionales para animaciones -->
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>