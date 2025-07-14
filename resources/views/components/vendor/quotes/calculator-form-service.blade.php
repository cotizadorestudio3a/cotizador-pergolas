@props([
    'clients' => '',
    'selectedCuadricula' => null,
    'availableServices' => '',
    'availableVariants' => '',
    'pvp' => 0,
    'iva' => 0,
    'total' => 0,
    'added_services' => [],
    'activeServiceIndex',
])

<div class="min-h-screen flex flex-col">
    <!-- Contenido principal que se expande -->
    <div class="flex-1">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
            <!-- FORMULARIO -->
            <div
                class="md:col-span-2 bg-white p-6 rounded-xl shadow transition-all duration-300 ease-in-out h-[calc(100vh-100px)] overflow-y-auto">
                <div class="space-y-2 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Ingresa la información en los siguientes campos</h2>
                    <p class="text-gray-600">Completa todos los campos requeridos para generar tu cotización</p>
                </div>

                @foreach ($added_services as $index => $servicio)
                    <div class="mb-8 border border-gray-200 rounded-xl p-4">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <span class="font-semibold text-gray-800">Servicio #{{ $index + 1 }}</span>
                                <span class="text-sm text-gray-500 ml-2">({{ $servicio['selected_cuadricula'] }})</span>
                            </div>
                            @if ($activeServiceIndex !== $index)
                                <flux:button size="sm"
                                    wire:click="$set('activeServiceIndex', {{ $index }})">
                                    Editar
                                </flux:button>
                            @endif
                        </div>

                        @if ($activeServiceIndex === $index)
                            <!-- CLIENTE (solo para el primero si quieres) -->
                            @if ($index === 0)
                                <div class="mb-6">
                                    <label class="block mb-2 text-sm font-medium text-gray-900">Selecciona un cliente
                                        *</label>
                                    <flux:select wire:model="client_id" placeholder="Selecciona al cliente">
                                        <flux:select.option value="">Selecciona</flux:select.option>
                                        @foreach ($clients as $client)
                                            <flux:select.option value="{{ $client->id }}">{{ $client->name }}
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </div>
                            @endif

                            <!-- Inputs de pérgola -->
                            <x-vendor.quotes.inputs-pergola />

                            <!-- Inputs de cuadrícula -->
                            @switch($servicio['selected_cuadricula'])
                                @case('cuadricula')
                                    <x-vendor.quotes.inputs-cuadricula />
                                @break

                                @case('cuadricula_trama')
                                    <x-vendor.quotes.inputs-cuadricula-trama />
                                @break
                            @endswitch
                        @endif
                    </div>
                @endforeach


            </div> <!-- end col-span-2 -->

            <!-- RESUMEN -->
                <div class="bg-white p-6 rounded-xl shadow transition-all duration-300 ease-in-out sticky top-6">
                    <h3 class="text-2xl font-bold mb-6 text-gray-900">Resumen</h3>

                    <!-- Sección de información seleccionada -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Servicios seleccionados</h4>

                        @forelse($added_services as $index => $servicio)
                            @php
                                $serviceName =
                                    collect($availableServices)->firstWhere('id', $servicio['service_id'])['name'] ??
                                    'Desconocido';
                                $variantName =
                                    collect($availableVariants)->firstWhere('id', $servicio['variant_id'])['name'] ??
                                    'Desconocido';
                            @endphp

                            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>

                                <span class="font-medium">
                                    {{ $serviceName }} {{ $variantName }} {{ $servicio['selected_cuadricula'] }}
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No se ha agregado ningún servicio.</p>
                        @endforelse
                    </div>


                    <!-- Cálculos -->
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">PVP</span>
                            <span class="font-medium text-gray-900">${{ number_format($pvp, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">IVA (15%)</span>
                            <span class="font-medium text-gray-900">${{ number_format($iva, 2) }}</span>
                        </div>
                        <hr class="border-gray-200">
                        <div class="flex justify-between text-lg font-bold">
                            <span class="text-gray-900">Total</span>
                            <span class="text-primary">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <flux:button variant='primary' wire:click="calcularTotal"
                            class="transition-all duration-200 hover:shadow-sm">
                            Calcular
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección fija en la parte inferior -->
        <div class="sticky bottom-0 bg-white border-t border-gray-200 p-6 mt-8">
            <div class="flex justify-between items-center">

                <div class="mt-4">
                    <flux:modal.trigger name="add-service-modal">
                        <a href="#" wire:click="startAddService"
                            class="text-primary text-sm font-medium hover:text-primary/80 transition-colors">+
                            agregar
                            otro servicio
                        </a>
                    </flux:modal.trigger>
                </div>

                <div class="flex items-center gap-2">
                    <flux:button wire:click="decrementStep" variant="ghost">

                        <span class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7">
                                </path>
                            </svg>

                            <span>Volver atrás</span>

                        </span>
                    </flux:button>

                    <flux:button icon="arrow-right" wire:click="generatePDFFiles" variant="primary"
                        class="min-w-[120px] transition-all duration-200 hover:shadow-sm">
                        <span class="flex items-center space-x-2">
                            <span>Finalizar</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </span>
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
