<div>
    <div class=" sm:px-6 lg:px-8">

        <div class="bg-white p-8 rounded-3xl">
            <h1 class="font-semibold text-2xl">Nueva Cotización</h1>
            <p class="text-gray-500">Ingresa los datos de la cotización.</p>
        </div>

        <div class="px-6 py-10 bg-white mt-6 rounded-2xl">

            @if ($step === 1)
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-1">Selecciona el servicio a cotizar</h2>
                    <p class="text-sm text-gray-500">A continuacion, sigue los pasos para realizar una cotización.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($services as $service)
                        <div class="rounded-2xl p-4 cursor-pointer
               {{ $selectedService === $service->id ? 'ring-2 ring-primary' : '' }}"
                            wire:click="$set('selectedService', {{ $service->id }})">
                            <div class="flex items-center gap-4 mb-6">
                                <img src="{{ asset('img/img1.webp') }}" class="w-24 h-24 rounded-full object-cover">
                                <h3 class="text-lg font-bold">{{ $service->name }}</h3>
                            </div>

                            {{-- se muestran los colores solo para el servicio actualmente seleccionado --}}
                            @if ($selectedService === $service->id)
                                <flux:radio.group wire:model="selectedColor.{{ $service->id }}"
                                    label="Selecciona un color…">
                                    <flux:radio value="azul" label="Azul" />
                                    <flux:radio value="negro" label="Negro" />
                                    <flux:radio value="blanco" label="Blanco" />
                                </flux:radio.group>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-10 text-right">
                    <flux:button wire:click="irPasoSiguiente">Siguiente</flux:button>
                </div>
        </div>

        @endif

        @if ($step === 2)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($variants as $variant)
                    <div class="rounded p-4 cursor-pointer shadow transition-all duration-300
                    {{ $selectedVariant === $variant->id ? 'ring-2 ring-primary bg-gray-50' : 'hover:ring-1 hover:ring-gray-300' }}"
                        wire:click="$set('selectedVariant', {{ $variant->id }})">
                        <h3 class="text-lg font-bold">
                            {{ $variant->service->name }}
                            <span class="text-sm text-gray-500">{{ $variant->name }}</span>
                        </h3>

                        @if ($selectedVariant === $variant->id)
                            <div class="mt-4">
                                <flux:radio.group wire:model="selectedCuadricula" label="Selecciona">
                                    <flux:radio value="cuadricula" label="Cuadrícula" />
                                    <flux:radio value="cuadricula_trama" label="Cuadrícula con trama" />
                                    <flux:radio value="sin_cuadricula" label="Sin Cuadrícula" />
                                </flux:radio.group>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6 text-right">
                <flux:button wire:click="irPasoSiguiente">Continuar</flux:button>
            </div>
        @endif


        @if ($step === 3)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                <!-- FORMULARIO -->
                <div class="md:col-span-2 bg-white p-6 rounded-xl shadow">
                    <h2 class="text-xl font-bold mb-1">Ingresa la información en los siguientes campos</h2>
                    <p class="text-sm text-gray-500 mb-6">lorem ipsum lorem ipsum lorem ipsum</p>

                    <!-- CLIENTE -->
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-medium">Selecciona un cliente *</label>
                        <flux:select wire:model="clienteId" placeholder="Selecciona al cliente">
                            <flux:select.option value="">Selecciona</flux:select.option>
                            @foreach ($clients as $client)
                                <flux:select.option value="{{ $client->id }}">{{ $client->name }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>

                    <!-- DATOS DE LA PÉRGOLA -->
                    <div>
                        <div>
                            <h3 class="font-semibold text-sm mb-4">Ingresa la información de la pérgola.</h3>
                            <div class="grid grid-cols-2 gap-4 max-w-xs">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Medida A</label>
                                    <input type="text" wire:model="medidaA"
                                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Medida B</label>
                                    <input type="text" wire:model="medidaB"
                                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Alto</label>
                                    <input type="text" wire:model="alto"
                                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Columnas</label>
                                    <input type="text" wire:model="n_columnas"
                                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Bajantes</label>
                                    <input type="text" wire:model="n_bajantes"
                                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Anillos</label>
                                    <input type="text" wire:model="anillos"
                                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DATOS DE LA CUADRICULA -->

                    @if ($selectedCuadricula === 'cuadricula')
                        <flux:separator class="my-8" />

                        <div class="mt-6">
                            <h3 class="font-semibold text-sm mb-4">Ingresa la información de la cuadricula.</h3>
                            <div class="grid grid-cols-2 gap-4 max-w-xs">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Medida A</label>
                                    <input type="text" wire:model="medidaACuadricula"
                                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Medida B</label>
                                    <input type="text" wire:model="medidaBCuadricula"
                                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Distancia Palillaje</label>
                                    <input type="text" wire:model="distanciaPalillajeCuadricula"
                                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Alto</label>
                                    <input type="text" wire:model="altoCuadricula"
                                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mt-6">
                            <h3 class="font-semibold text-sm mb-4">Ingresa la información de la cuadricula trama.</h3>
                            <div class="grid grid-cols-2 gap-4 max-w-xs">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Medida A</label>
                                    <input type="text" wire:model="medidaACuadriculaTrama"
                                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Medida B</label>
                                    <input type="text" wire:model="medidaBCuadriculaTrama"
                                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Distancia Palillaje</label>
                                    <input type="text" wire:model="distanciaPalillajeCuadriculaTrama"
                                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Alto</label>
                                    <input type="text" wire:model="altoCuadriculaTrama"
                                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                            </div>
                        </div>
                    @endif


                    <div class="flex justify-end gap-4">
                        <div class="mt-4">
                            <a href="#" class="text-primary text-sm font-medium">+ agregar otro servicio</a>
                        </div>

                        <div class="mt-6">
                            <flux:button variant='primary' wire:click="calcularTotal">Calcular</flux:button>
                        </div>
                    </div>


                </div>

                <!-- RESUMEN -->
                <div class="bg-white p-6 rounded-xl shadow">
                    <h3 class="text-lg font-bold mb-4">Resumen</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>PVP</span>
                            <span>${{ number_format($pvp, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span>IVA (15%)</span>
                            <span>${{ number_format($iva, 2) }}</span>
                        </div>
                        <hr>
                        <div class="flex justify-between text-base font-semibold">
                            <span>Total</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <div class="mt-8 text-right">
                        <flux:button icon="arrow-right" wire:click="finalizar">Finalizar</flux:button>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
