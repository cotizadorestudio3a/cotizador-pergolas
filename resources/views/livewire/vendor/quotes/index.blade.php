<div>
    <div class=" sm:px-6 lg:px-8">

        <div class="bg-white p-8 rounded-3xl">
            <h1 class="font-semibold text-2xl">Nueva Cotización</h1>
            <p class="text-gray-500">Ingresa los datos de la cotización.</p>
        </div>

        <div class="px-6 py-10 bg-white mt-6 rounded-2xl">
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-1">Selecciona el servicio a cotizar</h2>
                <p class="text-sm text-gray-500">A continuacion, sigue los pasos para realizar una cotización.</p>
            </div>
            @if ($step === 1)

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
                    <div class="rounded p-4 cursor-pointer shadow
                {{ $selectedVariant === $variant->id ? 'ring-2 ring-primary' : '' }}"
                        wire:click="$set('selectedVariant', {{ $variant->id }})">
                        <h3 class="text-lg font-bold">{{ $variant->service->name }}  <span class="text-sm text-gray-500">{{ $variant->name }}</span></h3>
                        {{-- datos extra de la variante --}}
                    </div>
                @endforeach
            </div>

            <div class="mt-6 text-right">
                <flux:button wire:click="irPasoSiguiente">Continuar</flux:button>
            </div>
        @endif

         @if ($step === 3)
            <p>{{ $service['service_id'] }}</p>
        @endif

    </div>
</div>
