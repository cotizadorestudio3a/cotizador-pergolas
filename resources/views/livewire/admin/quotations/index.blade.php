<div>
    <!-- Header -->
    <div class="bg-white p-8 rounded-3xl mb-6">
        <h1 class="font-semibold text-2xl">Gestión de Cotizaciones</h1>
        <p class="text-gray-500">Gestiona todas las cotizaciones que se han ralizado en el sistema</p>
    </div>

    <!-- Content -->
    <div class="py-6">
        
        <!-- Filtros y Búsqueda -->
        <div class="bg-white rounded-3xl p-8 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Búsqueda General -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                    <flux:input 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="ID, cliente, DNI, teléfono, vendedor..."
                        class="w-full"
                    />
                </div>

                <!-- Filtro por Vendedor -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vendedor</label>
                    <flux:select wire:model.live="vendorFilter" class="w-full">
                        <flux:select.option value="">Todos los vendedores</flux:select.option>
                        @foreach($vendors as $vendor)
                            <flux:select.option value="{{ $vendor->id }}">{{ $vendor->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <!-- Filtro por Cliente -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente</label>
                    <flux:select wire:model.live="clientFilter" class="w-full">
                        <flux:select.option value="">Todos los clientes</flux:select.option>
                        @foreach($clients as $client)
                            <flux:select.option value="{{ $client->id }}">{{ $client->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                <!-- Filtro por Fecha -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                    <flux:input type="date" wire:model.live="dateFrom" class="w-full" />
                </div>
            </div>

            <!-- Segunda fila de filtros -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                    <flux:input type="date" wire:model.live="dateTo" class="w-full" />
                </div>
                
                <div class="flex items-end">
                    <flux:button wire:click="clearFilters" variant="outline" class="w-full">
                        Limpiar Filtros
                    </flux:button>
                </div>
            </div>
        </div>

        <!-- Tabla de Cotizaciones -->
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th wire:click="sortBy('id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center space-x-1">
                                    <span>ID</span>
                                    @if($sortBy === 'id')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            @if($sortDirection === 'asc')
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                            @else
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            @endif
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendedor</th>
                            <th wire:click="sortBy('total')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center space-x-1">
                                    <span>Total</span>
                                    @if($sortBy === 'total')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            @if($sortDirection === 'asc')
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                            @else
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            @endif
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortBy('created_at')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center space-x-1">
                                    <span>Fecha</span>
                                    @if($sortBy === 'created_at')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            @if($sortDirection === 'asc')
                                                <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>
                                            @else
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            @endif
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicios</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($quotations as $quotation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">#{{ $quotation->id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $quotation->client->name }}</div>
                                    <div class="text-sm text-gray-500">DNI: {{ number_format($quotation->client->dni, 0, '', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $quotation->user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">${{ number_format($quotation->total, 2) }}</div>
                                    <div class="text-sm text-gray-500">
                                        PVP: ${{ number_format($quotation->pvp, 2) }} | 
                                        IVA: ${{ number_format($quotation->iva, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $quotation->created_at->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $quotation->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $quotation->items->count() }} servicio(s)</div>
                                    <div class="text-sm text-gray-500">
                                        {{ $quotation->items->map(fn($item) => $item->service->name)->unique()->implode(', ') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <flux:button 
                                        wire:click="showDetails({{ $quotation->id }})" 
                                        variant="outline" 
                                        size="sm"
                                        class="text-blue-600 hover:text-blue-900"
                                    >
                                        Ver Detalles
                                    </flux:button>
                                    
                                    <flux:button 
                                        wire:click="deleteQuotation({{ $quotation->id }})" 
                                        variant="outline" 
                                        size="sm"
                                        class="text-red-600 hover:text-red-900"
                                        wire:confirm="¿Estás seguro de que deseas eliminar esta cotización?"
                                    >
                                        Eliminar
                                    </flux:button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center">
                                    <div class="text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay cotizaciones</h3>
                                        <p class="mt-1 text-sm text-gray-500">No se encontraron cotizaciones con los filtros aplicados.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($quotations->hasPages())
                <div class="px-6 py-3 border-t border-gray-200">
                    {{ $quotations->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de Detalles de Cotización -->
    @if($showQuotationDetails && $selectedQuotation)
        <flux:modal wire:model="showQuotationDetails" size="xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold">Detalles de Cotización #{{ $selectedQuotation->id }}</h2>
                    <flux:button wire:click="closeDetails" variant="ghost" size="sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </flux:button>
                </div>
                
                <!-- Información General -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Información del Cliente</h3>
                        <div class="bg-gray-50 p-3 rounded">
                            <p class="text-sm"><strong>Nombre:</strong> {{ $selectedQuotation->client->name }}</p>
                            <p class="text-sm"><strong>DNI:</strong> {{ number_format($selectedQuotation->client->dni, 0, '', '.') }}</p>
                            <p class="text-sm"><strong>Teléfono:</strong> {{ $selectedQuotation->client->phone }}</p>
                            <p class="text-sm"><strong>Provincia:</strong> {{ $selectedQuotation->client->province }}</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Información del Vendedor</h3>
                        <div class="bg-gray-50 p-3 rounded">
                            <p class="text-sm"><strong>Nombre:</strong> {{ $selectedQuotation->user->name }}</p>
                            <p class="text-sm"><strong>Email:</strong> {{ $selectedQuotation->user->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Totales -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Resumen Financiero</h3>
                    <div class="bg-gray-50 p-3 rounded">
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">PVP:</span>
                                <span class="font-medium">${{ number_format($selectedQuotation->pvp, 2) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">IVA:</span>
                                <span class="font-medium">${{ number_format($selectedQuotation->iva, 2) }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600">Total:</span>
                                <span class="font-bold text-lg">${{ number_format($selectedQuotation->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Servicios -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Servicios Incluidos</h3>
                    <div class="space-y-3">
                        @foreach($selectedQuotation->items as $item)
                            <div class="border border-gray-200 rounded p-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $item->service->name }}</h4>
                                        @if($item->color)
                                            <p class="text-sm text-gray-600">Color: {{ ucfirst($item->color) }}</p>
                                        @endif
                                        @if($item->cuadricula_type)
                                            <p class="text-sm text-gray-600">Cuadrícula: {{ ucfirst($item->cuadricula_type) }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium"></p>
                                    </div>
                                </div>
                                
                                @if($item->inputs)
                                    @php
                                        $inputs = is_array($item->inputs) ? $item->inputs : json_decode($item->inputs, true);
                                    @endphp
                                    @if($inputs)
                                        <div class="mt-2 pt-2 border-t border-gray-100">
                                            <p class="text-xs text-gray-500 mb-1">Configuración:</p>
                                            <div class="text-xs text-gray-600 space-y-1">
                                                @foreach($inputs as $key => $value)
                                                    @if(!is_array($value) && $value)
                                                        <span class="inline-block bg-gray-100 px-2 py-1 rounded mr-1">
                                                            {{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Información de Fechas -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="text-sm text-gray-500">
                        <p>Creado: {{ $selectedQuotation->created_at->format('d/m/Y H:i') }}</p>
                        <p>Actualizado: {{ $selectedQuotation->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                <!-- Botones de acción -->
                <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                    <flux:button wire:click="closeDetails" variant="outline">Cerrar</flux:button>
                </div>
            </div>
        </flux:modal>
    @endif

    <!-- Notificaciones -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif
</div>
