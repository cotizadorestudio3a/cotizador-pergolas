@props(['pdfs_generados'])

<div class="p-4 bg-white rounded-lg">
    <div class="space-y-2 mb-6">
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-gray-900">Tu cotización ha sido completada</h2>
            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <p class="text-gray-600">Ahora puedes descargar los archivos PDF de tu cotización</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Orden de Producción -->
        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Orden de Producción</h2>
                        <p class="text-sm text-gray-600">Documentos generados para materiales y ejecución</p>
                    </div>
                </div>
            </div>

            @foreach ($pdfs_generados as $pdf)
                @if (Str::contains($pdf['titulo'], 'Orden Producción'))
                    <div class="mb-4 border-t pt-4">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                <span class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ $pdf['titulo'] }}
                                </span>
                            </div>

                            <a href="{{ asset('storage/' . $pdf['path']) }}" target="_blank" download
                                class="inline-flex items-center space-x-2 px-4 py-2 bg-slate-900 text-white rounded-xl hover:bg-slate-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Descargar</span>
                            </a>
                        </div>

                        <div class="mt-2 text-xs text-gray-400 underline truncate">
                            <a href="{{ asset('storage/' . $pdf['path']) }}" target="_blank">
                                Archivo: {{ asset('storage/' . $pdf['path']) }}
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>


        <!-- Cotizacion del cliente -->
        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-slate-900 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Cotización del Cliente</h2>
                        <p class="text-sm text-gray-600">Documento comercial para presentar al cliente</p>
                    </div>
                </div>
            </div>

            @foreach ($pdfs_generados as $pdf)
                @if (Str::contains($pdf['titulo'], 'Cotización'))
                    <div class="mb-4 border-t pt-4">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                <span class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ $pdf['titulo'] }}
                                </span>
                            </div>

                            <a href="{{ asset('storage/' . $pdf['path']) }}" target="_blank" download
                                class="inline-flex items-center space-x-2 px-4 py-2 bg-slate-900 text-white rounded-xl hover:bg-slate-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Descargar PDF</span>
                            </a>
                        </div>

                        <div class="mt-2 text-xs text-gray-400 underline truncate">
                            <a href="{{ asset('storage/' . $pdf['path']) }}" target="_blank">
                                Archivo: {{ asset('storage/' . $pdf['path']) }}
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="flex items-center justify-end mt-6">
        <flux:button variant='ghost'>
            <span class="flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>

                <span>Volver al panel de administración</span>

            </span>
        </flux:button>
    </div>
</div>
