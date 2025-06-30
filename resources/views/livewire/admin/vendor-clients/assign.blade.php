<div class="space-y-6 max-w-7xl mx-auto">
    <div class="p-4">

        <div class="bg-white p-8 rounded-3xl mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Asignar clientes a {{ $vendedor->name }}</h2>
            <p class="text-gray-500">Selecciona los clientes que deseas asignar a este vendedor</p>
        </div>

      <!-- Contenedor principal con mejor espaciado -->
<div class="bg-white rounded-xl border border-gray-100 p-6 max-w-4xl mx-auto">
   
    <!-- Sección de clientes ya asignados (badges mejorados) -->
    @if($clientesAsignados->isNotEmpty())
        <div class="mb-8">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Clientes Asignados</h3>
                <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">
                    {{ $clientesAsignados->count() }}
                </span>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($clientesAsignados as $cliente)
                    <div class="group relative inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 text-sm font-medium hover:from-green-100 hover:to-emerald-100 transition-all duration-200">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span>{{ $cliente->name }}</span>
                        </div>
                        <button
                            type="button"
                            wire:click="removeClient({{ $cliente->id }})"
                            class="ml-2 inline-flex items-center justify-center w-5 h-5 rounded-full text-green-600 hover:bg-green-300 hover:text-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1 transition-all duration-200"
                            aria-label="Quitar cliente"
                        >
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Buscador -->
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input
                type="text"
                wire:model.live.debounce.500ms="query"
                placeholder="Buscar clientes por nombre..."
                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 placeholder-gray-500 bg-gray-50 focus:bg-white"
            >
            <!-- Indicador de búsqueda activa -->
            <div wire:loading wire:target="query" class="absolute right-3 top-3">
                <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>

        <!-- Lista de clientes disponibles -->
        <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Clientes Disponibles</h3>
            </div>
            
            @if($clientesDisponibles->isEmpty())
                <div class="p-8 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">No se encontraron clientes disponibles</p>
                    <p class="text-sm text-gray-400 mt-1">Intenta ajustar tu búsqueda</p>
                </div>
            @else
                <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                    @foreach($clientesDisponibles as $cliente)
                        <label class="flex items-center p-4 hover:bg-blue-50 transition-all duration-200 cursor-pointer group">
                            <div class="flex-shrink-0">
                                <input
                                    type="checkbox"
                                    wire:model="selectedClients"
                                    value="{{ $cliente->id }}"
                                    class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 focus:ring-2 transition-all duration-200"
                                >
                            </div>

                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="block text-gray-900 font-semibold group-hover:text-blue-900 transition-colors duration-200">
                                            {{ $cliente->name }}
                                        </span>
                                        <span class="block text-sm text-gray-500 group-hover:text-blue-600 transition-colors duration-200">
                                            {{ $cliente->email }}
                                        </span>
                                    </div>
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            @endif
        </div>
        <x-success-info-message class="me-3" on='vendor-clients-assigned'>
            Se realizó la acción correctamente.
        </x-success-info-message>

        <x-error-info-message class="me-3" on='vendor-clients-assigned-error'>
            x-text="error"
        </x-error-info-message>

        <!-- Panel de acciones mejorado -->
        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    @if(count($selectedClients) > 0)
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            <span class="text-sm font-semibold text-gray-700">
                                {{ count($selectedClients) }} cliente(s) seleccionado(s)
                            </span>
                        </div>
                    @else
                        <span class="text-sm text-gray-500">Selecciona clientes para asignar</span>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    @if(count($selectedClients) > 0)
                        <button
                            type="button"
                            wire:click="$set('selectedClients', [])"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-200"
                        >
                            Limpiar selección
                        </button>
                    @endif
                    
                    <button
                        type="submit"
                        class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        <span wire:loading.remove class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Guardar Asignación
                        </span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Guardando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
    </div>
</div>
