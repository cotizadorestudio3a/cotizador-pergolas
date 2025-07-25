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
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
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
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- PDFs Generados -->
                        @if($quotation->pdfs && $quotation->pdfs->count() > 0)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">PDFs de la Cotización</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="grid gap-3">
                                        @php
                                            $groupedPdfs = $quotation->pdfs->groupBy('pdf_type');
                                        @endphp
                                        
                                        @foreach(['comercial', 'produccion_pergola', 'produccion_cuadricula'] as $type)
                                            @if($groupedPdfs->has($type))
                                                <div class="mb-4">
                                                    <h4 class="text-md font-medium text-gray-900 mb-2">
                                                        @if($type === 'comercial')
                                                            PDF Comercial
                                                        @elseif($type === 'produccion_pergola')
                                                            PDFs de Producción - Pérgolas
                                                        @else
                                                            PDFs de Producción - Cuadrículas
                                                        @endif
                                                    </h4>
                                                    <div class="space-y-2">
                                                        @foreach($groupedPdfs[$type] as $pdf)
                                                            <div class="flex items-center justify-between bg-white rounded-lg border border-gray-200 p-3">
                                                                <div class="flex items-center space-x-3">
                                                                    <div class="flex-shrink-0">
                                                                        <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                                        </svg>
                                                                    </div>
                                                                    <div class="flex-1 min-w-0">
                                                                        <p class="text-sm font-medium text-gray-900 truncate">
                                                                            @if($pdf->quotationItem)
                                                                                {{ $pdf->quotationItem->service->name }} - {{ $pdf->quotationItem->serviceVariant->name }}
                                                                            @else
                                                                                {{ $type === 'comercial' ? 'Cotización Comercial' : 'PDF de Producción' }}
                                                                            @endif
                                                                        </p>
                                                                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                                            <span>{{ $pdf->created_at->format('d/m/Y H:i') }}</span>
                                                                            @if($pdf->file_size)
                                                                                <span>•</span>
                                                                                <span>{{ $pdf->getFormattedFileSize() }}</span>
                                                                            @endif
                                                                            @if($pdf->service_index)
                                                                                <span>•</span>
                                                                                <span>Servicio #{{ $pdf->service_index }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="flex items-center space-x-2">
                                                                    @if($pdf->fileExists())
                                                                        <a href="{{ $pdf->getPublicUrl() }}" 
                                                                           target="_blank"
                                                                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-100 rounded-md hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                                            </svg>
                                                                            Ver PDF
                                                                        </a>
                                                                        <a href="{{ $pdf->getPublicUrl() }}" 
                                                                           download="{{ basename($pdf->file_path) }}"
                                                                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                            </svg>
                                                                            Descargar
                                                                        </a>
                                                                    @else
                                                                        <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 bg-red-100 rounded-md">
                                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                                            </svg>
                                                                            No disponible
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">PDFs de la Cotización</h3>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <p class="text-sm text-yellow-800">
                                            No se han generado PDFs para esta cotización aún.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

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
