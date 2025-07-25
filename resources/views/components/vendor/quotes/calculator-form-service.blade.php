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
    'selectorColorVisible' => false,
    'servicioSelectorColor' => null,
    'indiceSelectorColor' => null,
    'selectedColor' => null,
    'inputsPorServicio' => [],
])

<div class="min-h-screen flex flex-col">
    <!-- Contenido principal que se expande -->
    <div class="flex-1">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
            <!-- FORMULARIO -->
            <div
                class="md:col-span-2 bg-white p-2 rounded-xl  transition-all duration-300 ease-in-out h-[calc(100vh-100px)] overflow-y-auto">
                <div class="space-y-2 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Ingresa la información en los siguientes campos</h2>
                    <p class="text-gray-600">Completa todos los campos requeridos para generar tu cotización</p>
                </div>

                <!-- Mensajes de error generales -->
                @if (session()->has('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h3 class="text-sm font-medium text-red-800 mb-1">Error de validación</h3>
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Errores de validación de Livewire -->
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h3 class="text-sm font-medium text-red-800 mb-2">Se encontraron los siguientes errores:</h3>
                                <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

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
                    @error('client_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                @foreach ($added_services as $index => $servicio)
                    <div class="mb-8 border border-gray-200 rounded-xl p-4">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <span class="font-bold text-gray-800">Servicio #{{ $index + 1 }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if ($activeServiceIndex !== $index)
                                    <flux:button size="sm"
                                        wire:click="$set('activeServiceIndex', {{ $index }})">
                                        Editar
                                    </flux:button>
                                @endif

                                @if (count($added_services) > 1)
                                    <flux:button size="sm" variant="danger"
                                        wire:click="removeService({{ $index }})"
                                        wire:confirm="¿Estás seguro de que quieres eliminar este servicio?">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </flux:button>
                                @endif
                            </div>
                        </div>

                        @if ($activeServiceIndex === $index)
                            <!-- Inputs de pérgola -->
                            <x-vendor.quotes.inputs-pergola :index="$index" :selectorColorVisible="$selectorColorVisible ?? false" :servicioSelectorColor="$servicioSelectorColor ?? null"
                                :indiceSelectorColor="$indiceSelectorColor ?? null" :selectedColor="$selectedColor ?? null" :inputsPorServicio="$inputsPorServicio ?? []" />

                            <!-- Inputs de cuadrícula -->
                            @switch($servicio['selected_cuadricula'])
                                @case('cuadricula')
                                    <x-vendor.quotes.inputs-cuadricula :index="$index" />
                                @break

                                @case('cuadricula_trama')
                                    <x-vendor.quotes.inputs-cuadricula-trama :index="$index" />
                                @break
                            @endswitch
                        @endif
                    </div>
                @endforeach

                @if(count($added_services) === 0)
                    <div class="mb-8 text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay servicios agregados</h3>
                        <p class="text-gray-500 mb-4">Comienza agregando un servicio para generar tu cotización</p>
                        <flux:modal.trigger name="add-service-modal">
                            <flux:button wire:click="startAddService" variant="primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Agregar primer servicio
                            </flux:button>
                        </flux:modal.trigger>
                    </div>
                @endif


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

                        <span class="text-gray-500 text-sm">Servicio #{{ $index + 1 }}</span>
                        <div class="mb-3 p-3 bg-white rounded-lg border border-gray-200">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="font-medium text-sm text-gray-800">
                                    {{ $serviceName }} - {{ $variantName }}
                                </span>
                            </div>

                            <div class="text-xs text-gray-500 mt-1">
                                <span>{{ ucfirst(str_replace('_', ' ', $servicio['selected_cuadricula'])) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No se ha agregado ningún servicio.</p>
                    @endforelse
                </div>

                <!-- Cálculos -->
                <div class="space-y-3">
                    @if ($total > 0)
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
                    @else
                        <div class="text-center text-gray-500 py-4">
                            <p class="text-sm">Presione calcular para ver el total de la cotización</p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end mt-6 space-x-2">
                    <flux:button variant='primary' wire:click="calcularTotal"
                        class="transition-all duration-200 hover:shadow-sm"
                        :disabled="count($added_services) === 0">
                        @if(count($added_services) > 0)
                            Calcular
                        @else
                            <span class="flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <span>Agregar servicio</span>
                            </span>
                        @endif
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        <span>Volver atrás</span>
                    </span>
                </flux:button>

                <flux:button icon="arrow-right" wire:click="generatePDFFiles" variant="primary"
                    class="min-w-[120px] transition-all duration-200 hover:shadow-sm"
                    :disabled="$total <= 0">
                    <span class="flex items-center space-x-2">
                        @if($total > 0)
                            <span>Finalizar</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <span>Calcular primero</span>
                        @endif
                    </span>
                </flux:button>
            </div>
        </div>
    </div>
</div>
