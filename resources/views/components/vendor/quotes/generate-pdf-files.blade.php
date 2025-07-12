@props([
    'pdf_orden_produccion',
    'pdf_lista_materiales'
])

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-white rounded-lg">
    <!-- Orden de Producción -->
    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Orden de Producción</h2>
                    <p class="text-sm text-gray-600">Documento con materiales necesarios</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    PDF
                </span>
            </div>

            <!-- Botón que descarga -->
            <a href="{{ asset('storage/' . $pdf_orden_produccion) }}" target="_blank" download
               class="inline-flex items-center space-x-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Descargar PDF</span>
            </a>
        </div>

        <div class="mt-3 text-xs text-gray-400">
            Archivo: {{ asset('storage/' . $pdf_orden_produccion) }}
        </div>
    </div>

    <!-- Lista de Materiales -->
    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-md transition-shadow duration-300">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-400 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Lista de Materiales</h2>
                    <p class="text-sm text-gray-600">Inventario y especificaciones</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    PDF
                </span>
            </div>

            <!-- Botón que descarga o abre el PDF -->
            <a href="{{ asset($pdf_orden_produccion) }}" target="_blank" download
               class="inline-flex items-center space-x-2 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Descargar PDF</span>
            </a>
        </div>

        <div class="mt-3 text-xs text-gray-400">
            Archivo: {{ asset($pdf_orden_produccion) }}
        </div>
    </div>
</div>
