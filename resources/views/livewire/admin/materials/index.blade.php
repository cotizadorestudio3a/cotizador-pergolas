<div>
    <!-- Header -->
    <div class="bg-white p-8 rounded-3xl mb-6">
        <h1 class="font-semibold text-2xl">Gestión de Materiales</h1>
        <p class="text-gray-500">Administra los precios de los materiales para las cotizaciones</p>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Actions Bar -->
    <div class="bg-white p-6 rounded-xl shadow mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Search -->
            <div class="flex-1 max-w-md">
                <flux:input wire:model.live="search" placeholder="Buscar materiales..." />
            </div>
            
            <!-- Actions -->
            <div class="flex gap-2">
                <flux:button variant="outline" wire:click="initializeDefaultMaterials">
                    Inicializar Materiales
                </flux:button>
                <flux:button variant="primary" wire:click="openCreateModal">
                    Nuevo Material
                </flux:button>
            </div>
        </div>
    </div>

    <!-- Materials Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Código
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Material
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Unidad
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Precio Unitario
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($materials as $material)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-mono font-medium text-gray-900">
                                    {{ $material->code ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ ucfirst(str_replace('_', ' ', $material->name)) }}
                                </div>
                                <div class="text-xs text-gray-500">{{ $material->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $material->unit }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="font-medium">${{ number_format($material->unit_price, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <flux:button variant="ghost" size="sm" wire:click="openEditModal({{ $material->id }})">
                                        Editar
                                    </flux:button>
                                    <flux:button variant="danger" size="sm" 
                                        wire:click="deleteMaterial({{ $material->id }})"
                                        wire:confirm="¿Estás seguro de eliminar este material?">
                                        Eliminar
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-900 mb-2">No hay materiales</p>
                                    <p class="text-gray-500 mb-4">Comienza agregando algunos materiales para gestionar sus precios</p>
                                    <flux:button variant="primary" wire:click="initializeDefaultMaterials">
                                        Inicializar Materiales por Defecto
                                    </flux:button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($materials->hasPages())
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $materials->links() }}
            </div>
        @endif
    </div>

    <!-- Create Material Modal -->
    <flux:modal wire:model="showCreateModal" class="max-w-md">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Crear Nuevo Material</h3>
            
            <form wire:submit="createMaterial" class="space-y-4">
                <div>
                    <flux:field>
                        <flux:label>Código del Material</flux:label>
                        <flux:input wire:model="code" placeholder="ej: VIG_PRIN, COL_ALU" />
                        <flux:error name="code" />
                    </flux:field>
                </div>
                
                <div>
                    <flux:field>
                        <flux:label>Nombre del Material</flux:label>
                        <flux:input wire:model="name" placeholder="ej: viga_principal" />
                        <flux:error name="name" />
                    </flux:field>
                </div>
                
                <div>
                    <flux:field>
                        <flux:label>Unidad</flux:label>
                        <flux:input wire:model="unit" placeholder="ej: unidad, m², kg" />
                        <flux:error name="unit" />
                    </flux:field>
                </div>
                
                <div>
                    <flux:field>
                        <flux:label>Precio Unitario ($)</flux:label>
                        <flux:input wire:model="unit_price" type="number" step="0.01" min="0" placeholder="0.00" />
                        <flux:error name="unit_price" />
                    </flux:field>
                </div>
                
                <div class="flex justify-end gap-2 pt-4">
                    <flux:button variant="ghost" wire:click="closeCreateModal">
                        Cancelar
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Crear Material
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    <!-- Edit Material Modal -->
    <flux:modal wire:model="showEditModal" class="max-w-md">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Editar Material</h3>
            
            <form wire:submit="updateMaterial" class="space-y-4">
                <div>
                    <flux:field>
                        <flux:label>Código del Material</flux:label>
                        <flux:input wire:model="code" placeholder="ej: VIG_PRIN, COL_ALU" />
                        <flux:error name="code" />
                    </flux:field>
                </div>
                
                <div>
                    <flux:field>
                        <flux:label>Nombre del Material</flux:label>
                        <flux:input wire:model="name" placeholder="ej: viga_principal" />
                        <flux:error name="name" />
                    </flux:field>
                </div>
                
                <div>
                    <flux:field>
                        <flux:label>Unidad</flux:label>
                        <flux:input wire:model="unit" placeholder="ej: unidad, m², kg" />
                        <flux:error name="unit" />
                    </flux:field>
                </div>
                
                <div>
                    <flux:field>
                        <flux:label>Precio Unitario ($)</flux:label>
                        <flux:input wire:model="unit_price" type="number" step="0.01" min="0" placeholder="0.00" />
                        <flux:error name="unit_price" />
                    </flux:field>
                </div>
                
                <div class="flex justify-end gap-2 pt-4">
                    <flux:button variant="ghost" wire:click="closeEditModal">
                        Cancelar
                    </flux:button>
                    <flux:button type="submit" variant="primary">
                        Actualizar Material
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>
