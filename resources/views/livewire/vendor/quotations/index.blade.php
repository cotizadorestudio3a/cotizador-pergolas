<div>
    <div class="sm:px-6 lg:px-8">
        <!-- Header con estadísticas -->
        <div class="bg-white p-8 rounded-3xl mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="font-semibold text-2xl text-gray-900">Mis Cotizaciones</h1>
                    <p class="text-gray-500 mt-1">Gestiona y revisa todas tus cotizaciones</p>
                </div>
                <div class="flex space-x-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $statistics['total_quotations'] }}</div>
                        <div class="text-sm text-gray-500">Total</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">${{ number_format($statistics['total_amount'], 2) }}</div>
                        <div class="text-sm text-gray-500">Valor Total</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $statistics['this_month'] }}</div>
                        <div class="text-sm text-gray-500">Este Mes</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros y búsqueda -->
        <div class="bg-white p-6 rounded-xl mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 md:space-x-4">
                <!-- Búsqueda -->
                <div class="flex-1 max-w-md">
                    <flux:input 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Buscar por cliente o DNI..."
                        icon="magnifying-glass"
                    />
                </div>

                <!-- Filtros -->
                <div class="flex space-x-4">
                    <!-- Filtro por cliente -->
                    <flux:select wire:model.live="selectedClientFilter" placeholder="Todos los clientes">
                        <flux:select.option value="">Todos los clientes</flux:select.option>
                        @foreach($clients as $client)
                            <flux:select.option value="{{ $client->id }}">{{ $client->name }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <!-- Botón para limpiar filtros -->
                    <flux:button wire:click="clearFilters" variant="ghost" size="sm">
                        Limpiar filtros
                    </flux:button>
                </div>

                <!-- Botón para nueva cotización -->
                <div>
                    <flux:button 
                        href="{{ route('vendor.quotes.index') }}" 
                        variant="primary"
                        icon="plus"
                    >
                        Nueva Cotización
                    </flux:button>
                </div>
            </div>
        </div>

        <!-- Tabla de cotizaciones -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            @if($quotations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button wire:click="sortBy('id')" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>ID</span>
                                        @if($sortBy === 'id')
                                            @if($sortDirection === 'asc')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cliente
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Servicios
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button wire:click="sortBy('total')" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Total</span>
                                        @if($sortBy === 'total')
                                            @if($sortDirection === 'asc')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button wire:click="sortBy('created_at')" class="flex items-center space-x-1 hover:text-gray-700">
                                        <span>Fecha</span>
                                        @if($sortBy === 'created_at')
                                            @if($sortDirection === 'asc')
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @endif
                                    </button>
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($quotations as $quotation)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $quotation->client->name }}
                                        </div>
                                
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">
                                            {{ $quotation->quotationItems->count() }} servicio(s)
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            @foreach($quotation->quotationItems->take(2) as $item)
                                                <span class="inline-block bg-gray-100 rounded-full px-2 py-1 text-xs font-medium text-gray-800 mr-1 mb-1">
                                                    {{ $item->service->name }} - {{ $item->serviceVariant->name }}
                                                </span>
                                            @endforeach
                                            @if($quotation->quotationItems->count() > 2)
                                                <span class="text-xs text-gray-500">
                                                    +{{ $quotation->quotationItems->count() - 2 }} más
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">
                                            ${{ number_format($quotation->total, 2) }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            PVP: ${{ number_format($quotation->pvp, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>{{ $quotation->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs">{{ $quotation->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <flux:button 
                                                wire:click="$dispatch('openViewModal', { quotationId: {{ $quotation->id }} })"
                                                size="sm" 
                                                variant="ghost"
                                            >
                                                Ver
                                            </flux:button>
                                            
                                            <flux:button 
                                                wire:click="$dispatch('openViewModal', { quotationId: {{ $quotation->id }} })"
                                                size="sm" 
                                                variant="outline"
                                                icon="eye"
                                            >
                                                Detalles
                                            </flux:button>
                                            
                                            <flux:button 
                                                wire:click="deleteQuotation({{ $quotation->id }})"
                                                wire:confirm="¿Estás seguro de que deseas eliminar esta cotización?"
                                                size="sm"
                                                variant="ghost"
                                                icon="trash"
                                                class="text-red-600 hover:text-red-800"
                                            >
                                                Eliminar
                                            </flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $quotations->links() }}
                </div>
            @else
                <!-- Estado vacío -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay cotizaciones</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(empty($search) && !$selectedClientFilter)
                            Comienza creando tu primera cotización.
                        @else
                            No se encontraron cotizaciones que coincidan con los filtros aplicados.
                        @endif
                    </p>
                    <div class="mt-6">
                        @if(empty($search) && !$selectedClientFilter)
                            <flux:button 
                                href="{{ route('vendor.quotes.index') }}" 
                                variant="primary"
                                icon="plus"
                            >
                                Nueva Cotización
                            </flux:button>
                        @else
                            <flux:button wire:click="clearFilters" variant="ghost">
                                Limpiar filtros
                            </flux:button>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Modal para ver detalles de cotización -->
        <x-vendor.quotations.view-modal />
    </div>

    <!-- Mensajes de estado -->
    @if (session()->has('message'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('message') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif
</div>
