<div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
    @if ($clients->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            DNI
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Teléfono
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Provincia
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha de registro
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Acciones</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($clients as $client)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $client->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ number_format($client->dni, 0, '', '.') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $client->phone }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $client->province }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div>{{ $client->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs">{{ $client->created_at->format('H:i') }}</div>
                            </td>

                            @if (request()->routeIs('vendor.clients.index'))
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div x-data="{ confirming: false }" class="relative">
                                        <!-- Botón principal -->
                                        <flux:button variant="ghost" size="sm" icon="trash"
                                            class="text-red-600 hover:text-red-800" @click="confirming = !confirming">
                                            Eliminar
                                        </flux:button>

                                        <!-- Popover flotante -->
                                        <div x-show="confirming" x-transition @click.outside="confirming = false"
                                            class="absolute z-20 mt-2 -right-6 w-56 bg-white shadow-lg rounded-lg border border-gray-200 p-3 flex flex-col gap-3">
                                            <p class="text-sm text-gray-700 leading-5 whitespace-normal">¿Seguro que
                                                quieres eliminar este cliente?</p>

                                            <div class="flex flex-col gap-2">
                                                <flux:button variant="danger" wire:click="delete({{ $client->id }})"
                                                    @click="confirming = false">
                                                    Sí, eliminar
                                                </flux:button>
                                                <flux:button variant="ghost" @click="confirming = false">Cancelar
                                                </flux:button>

                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @endif

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $clients->links() }}
        </div>
    @else
        <!-- Estado vacío -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay clientes registrados</h3>
            <p class="mt-1 text-sm text-gray-500">
                Agrega nuevos clientes en el sistema para comenzar a generar cotizaciones.
            </p>
        </div>
    @endif
</div>
