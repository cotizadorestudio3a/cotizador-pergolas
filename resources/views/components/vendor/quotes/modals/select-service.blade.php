<div class="space-y-4 max-h-[70vh] overflow-y-auto">
    <!-- Header más compacto -->
    <div class="space-y-1">
        <h2 class="text-lg font-semibold text-gray-900">Selecciona el servicio a cotizar</h2>
        <p class="text-sm text-gray-600">Sigue los pasos para realizar una cotización.</p>
    </div>

    <!-- Grid de servicios más compacto -->
    <div class="grid grid-cols-2 gap-3">
        @foreach ($services as $service)
            <div class="group relative overflow-hidden rounded-lg border transition-all duration-300 ease-in-out
                {{ $selectedService === $service->id
                    ? 'border-primary/30 bg-primary/5 shadow-sm'
                    : 'border-gray-200 bg-white hover:border-gray-300 hover:shadow-sm' }}"
                wire:click="$set('selectedService', {{ $service->id }})">

                <!-- Indicador de selección más pequeño -->
                @if ($selectedService === $service->id)
                    <div
                        class="absolute top-3 right-3 w-4 h-4 bg-primary rounded-full flex items-center justify-center shadow-sm">
                        <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                @endif

                <!-- Contenido principal más compacto -->
                <div class="p-4 cursor-pointer">
                    <!-- Encabezado del servicio -->
                    <div class="flex items-center gap-3 mb-3">
                        <div class="relative">
                            @php
                                $corintiaImagePath =
                                    $service->id == 1
                                        ? asset('img/img_corintia.webp')
                                        : asset('img/img_corrediza.webp');
                            @endphp
                           
                            <img src="{{ $corintiaImagePath }}"
                                class="w-12 h-12 rounded-full object-cover ring-1 ring-gray-100 transition-all duration-300
                                 {{ $selectedService === $service->id ? 'ring-primary/40 ring-2' : 'group-hover:ring-gray-200' }}">
                        </div>

                        <div class="flex-1">
                            <h3
                                class="text-base font-semibold text-gray-900 group-hover:text-primary transition-colors">
                                {{ $service->name }}
                            </h3>

                            <!-- Descripción adicional opcional -->
                            @if (isset($service->description))
                                <p class="text-xs text-gray-600 mt-0.5">{{ $service->description }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Opciones de color expandidas con animación -->
                    <div
                        class="overflow-hidden transition-all duration-300 ease-in-out cursor-pointer
                        {{ $selectedService === $service->id ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}">

                        @if ($selectedService === $service->id)
                            <div class="pt-3 border-t border-gray-200 animate-fade-in">
                                <div class="space-y-2">
                                    <flux:radio.group wire:model="selectedColor" class="space-y-1"
                                        label="Seleccione un color">
                                        <div
                                            class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50 transition-colors">
                                            <flux:radio value="azul" label="Azul" checked />
                                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                        </div>

                                        <div
                                            class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50 transition-colors">
                                            <flux:radio value="negro" label="Negro" />
                                            <div class="w-3 h-3 bg-black rounded-full"></div>
                                        </div>

                                        <div
                                            class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50 transition-colors">
                                            <flux:radio value="blanco" label="Blanco" />
                                            <div class="w-3 h-3 bg-white rounded-full border border-gray-300"></div>
                                        </div>

                                        <div
                                            class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50 transition-colors">
                                            <flux:radio value="gris" label="Gris" />
                                            <div class="w-3 h-3 bg-gray-300 rounded-full border border-gray-300"></div>
                                        </div>

                                        <div
                                            class="flex items-center space-x-3 p-2 rounded-md hover:bg-gray-50 transition-colors">
                                            <flux:radio value="rojo" label="Rojo" />
                                            <div class="w-3 h-3 bg-red-500 rounded-full border border-gray-300"></div>
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
                @if ($selectedService)
                    <span class="flex items-center">
                        <svg class="w-3 h-3 text-green-500 mr-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
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
