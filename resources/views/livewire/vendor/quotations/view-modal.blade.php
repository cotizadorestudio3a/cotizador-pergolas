<div x-data="{ showModal: @entangle('showModal') }">
    <!-- Backdrop -->
    <div x-show="showModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500 bg-opacity-75 z-50"
         style="display: none;">
    </div>

    <!-- Modal -->
    <div x-show="showModal"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                @if($quotation)
                    <div class="p-6">
                        <!-- Header del modal -->
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">
                                    Cotización #{{ str_pad($quotation->id, 4, '0', STR_PAD_LEFT) }}
                                </h2>
                                <p class="text-gray-500 mt-1">
                                    Creada el {{ $quotation->created_at->format('d/m/Y') }} a las {{ $quotation->created_at->format('H:i') }}
                                </p>
                            </div>
                            <button wire:click="closeModal" 
                                    class="text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Información del cliente -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Información del Cliente</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nombre:</span>
                                    <p class="text-sm text-gray-900">{{ $quotation->client->name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">DNI:</span>
                                    <p class="text-sm text-gray-900">{{ $quotation->client->dni }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Teléfono:</span>
                                    <p class="text-sm text-gray-900">{{ $quotation->client->phone ?? 'No especificado' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Provincia:</span>
                                    <p class="text-sm text-gray-900">{{ $quotation->client->province ?? 'No especificada' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Servicios cotizados -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Servicios Cotizados</h3>
                            <div class="space-y-3">
                                @foreach($quotation->quotationItems as $item)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="font-medium text-gray-900">
                                                    {{ $item->service->name }} - {{ $item->serviceVariant->name }}
                                                </h4>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    Precio calculado: ${{ number_format($item->calculated_price, 2) }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Incluido
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Resumen financiero -->
                        <div class="bg-blue-50 rounded-lg p-4 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Resumen Financiero</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">PVP (sin IVA):</span>
                                    <span class="font-medium text-gray-900">${{ number_format($quotation->pvp, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">IVA (15%):</span>
                                    <span class="font-medium text-gray-900">${{ number_format($quotation->iva, 2) }}</span>
                                </div>
                                <hr class="border-gray-200">
                                <div class="flex justify-between text-lg font-bold">
                                    <span class="text-gray-900">Total:</span>
                                    <span class="text-blue-600">${{ number_format($quotation->total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="flex justify-end space-x-3">
                            <button wire:click="closeModal" 
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cerrar
                            </button>
                            
                            <button onclick="window.print()" 
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Imprimir
                            </button>
                            
                            <a href="{{ route('vendor.quotes.index') }}" 
                               class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nueva Cotización
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
