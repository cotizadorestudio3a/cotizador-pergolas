<div class="space-y-6 min-h-screen flex flex-col">
    <!-- Header con descripción -->
    <div class="space-y-2">
        <h2 class="text-2xl font-bold text-gray-900">Selecciona tu servicio</h2>
        <p class="text-gray-600">Elige el servicio que mejor se adapte a tus necesidades</p>
    </div>

    <!-- Grid de variantes  -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 flex-1">
        @foreach ($variants as $variant)
            @php
                $isSelected = $selectedVariant === $variant->id;
                $isRestrictedVariant = in_array($variant->id, [3, 4]); // variantes restringidas (id desde la DB)
                $cardClasses = $isSelected 
                    ? 'border-primary/30 bg-primary/5 shadow-sm'
                    : 'border-gray-100 bg-white hover:border-gray-200 hover:shadow-sm';
            @endphp

            <div class="group relative overflow-hidden rounded-lg border transition-all duration-300 ease-in-out {{ $cardClasses }}"
                wire:click="$set('selectedVariant', {{ $variant->id }})">

                @include('components.vendor.quotes.partials.selection-indicator', ['isSelected' => $isSelected])

                <!-- Contenido principal -->
                <div class="p-6 cursor-pointer">
                    @include('components.vendor.quotes.partials.service-header', ['variant' => $variant])

                    @if ($isSelected)
                        @include('components.vendor.quotes.partials.grid-options', [
                            'isRestrictedVariant' => $isRestrictedVariant
                        ])
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Botón de continuar mejorado -->
    <div class="sticky bottom-0 bg-white flex justify-between items-center p-8 border-t border-gray-200">
        <div class="text-sm text-gray-600">
            @if ($selectedVariant)
                <span class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Servicio seleccionado
                </span>
            @else
                <span class="text-gray-400">Selecciona un servicio para continuar</span>
            @endif
        </div>

        <div class="flex items-center gap-2">

            <flux:button wire:click="decrementStep" variant="ghost">

                <span class="flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>

                    <span>Volver atrás</span>

                </span>
            </flux:button>

            <flux:button wire:click="irPasoSiguiente" variant="primary" :disabled="!$selectedVariant"
                class="min-w-[120px] {{ !$selectedVariant ? 'opacity-50 cursor-not-allowed' : '' }}">

                <span class="flex items-center space-x-2">
                    <span>Continuar</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            </flux:button>
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
