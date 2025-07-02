<div>
    <div class=" sm:px-6 lg:px-8">

        <div class="bg-white p-8 rounded-3xl">
            <h1 class="font-semibold text-2xl">Nueva Cotización</h1>
            <p class="text-gray-500">Ingresa los datos de la cotización.</p>
        </div>

       @php
    $servicios = [
        [
            'nombre' => 'Pérgola con corintia',
            'imagen' => 'corintia.jpg',
            'colores' => ['Negro', 'Azul'],
        ],
        [
            'nombre' => 'Pérgola chapero',
            'imagen' => 'chapero.jpg',
            'colores' => [],
        ],
        [
            'nombre' => 'Pérgola corrediza',
            'imagen' => 'corrediza.jpg',
            'colores' => [],
        ],
    ];
@endphp

<div class="px-6 py-10">
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-1">Selecciona el servicio a cotizar</h2>
        <p class="text-sm text-gray-500">lorem ipsum lorem ipsum lorem ipsum</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($servicios as $index => $servicio)
            <div class="bg-white rounded-2xl shadow p-4">
                <div class="flex items-center gap-4 cursor-pointer" wire:click="$set('servicioSeleccionado', {{ $index }})">
                    <img src="/storage/{{ $servicio['imagen'] }}" alt="{{ $servicio['nombre'] }}" class="w-14 h-14 rounded-full object-cover">
                    <h3 class="text-lg font-bold">{{ $servicio['nombre'] }}</h3>
                   <flux:dropdown>
    <flux:button icon:trailing="chevron-down">Permissions</flux:button>

    <flux:menu>
        <flux:menu.checkbox wire:model="read" checked>Read</flux:menu.checkbox>
        <flux:menu.checkbox wire:model="write" checked>Write</flux:menu.checkbox>
        <flux:menu.checkbox wire:model="delete">Delete</flux:menu.checkbox>
    </flux:menu>
</flux:dropdown>
                </div>

                @if(count($servicio['colores']))
                    <div class="mt-4">
                        <p class="text-sm font-medium mb-2">Colores</p>
                        @foreach($servicio['colores'] as $color)
                            <label class="flex items-center space-x-2 mb-1">
                                <input type="radio" wire:model="colorSeleccionado" value="{{ $color }}" class="text-blue-500">
                                <span>{{ $color }}</span>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-10 text-right">
        <flux:button wire:click="irPasoSiguiente">Siguiente</flux:button>
    </div>
</div>

    </div>
</div>