<div class="space-y-6 max-w-4xl mx-auto">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Asignar clientes a {{ $vendedor->name }}</h2>
        <p class="text-gray-600 mb-6">Selecciona los clientes que deseas asignar a este vendedor</p>

        <!-- Sección de clientes ya asignados (badges) -->
        @if($clientesAsignados->isNotEmpty())
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Clientes ya asignados:</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($clientesAsignados as $cliente)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium">
                            {{ $cliente->name }}
                            <button
                                type="button"
                                wire:click="removeClient({{ $cliente->id }})"
                                class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full text-green-600 hover:bg-green-200 focus:outline-none"
                                aria-label="Quitar cliente"
                            >
                                <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <form wire:submit="search">
            <input class="bg-amber-300" type="text" wire:model.live.debounce.500ms="query">
        </form>

        <form wire:submit.prevent="save" class="space-y-6">
            <!-- Buscador para filtrar clientes -->
            <div class="relative">

                <div class="absolute right-3 top-2.5 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Lista de clientes disponibles -->
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                @if($clientesDisponibles->isEmpty())
                    <p class="p-4 text-gray-500 text-center">No se encontraron clientes disponibles</p>
                @else
                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                        @foreach($clientesDisponibles as $cliente)
                            <label class="flex items-center p-4 hover:bg-gray-50 transition-colors cursor-pointer">
                                <input
                                    type="checkbox"
                                    wire:model="selectedClients"
                                    value="{{ $cliente->id }}"
                                    class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 border-gray-300"
                                >
                                <div class="ml-3">
                                    <span class="block text-gray-900 font-medium">{{ $cliente->name }}</span>
                                    <span class="block text-sm text-gray-500">{{ $cliente->email }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Acciones -->
            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <div>
                    @if(count($selectedClients) > 0)
                        <span class="text-sm font-medium text-gray-700">
                            {{ count($selectedClients) }} cliente(s) seleccionado(s)
                        </span>
                    @endif
                </div>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg shadow-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                >
                    <span wire:loading.remove>Guardar asignación</span>
                    <span wire:loading>
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
