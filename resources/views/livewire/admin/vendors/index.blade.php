<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <h1 class="font-semibold text-2xl">Vendedores</h1>
        <p class="text-gray-500">Gestiona los vendedores desde aqui.</p>

        <div class="mb-4 mt-8">
            <flux:modal.trigger name="create">
                <flux:button variant="primary">Crear nuevo vendedor</flux:button>
            </flux:modal.trigger>

            <flux:modal name="create" class="md:w-96" variant="flyout">
                <form wire:submit="save" class="space-y-6"> {{-- Aquí va el evento Livewire --}}
                    <div>
                        <flux:heading size="lg">Crear vendedor</flux:heading>
                        <flux:text class="mt-2">Ingresa los datos del vendedor.</flux:text>
                    </div>

                    <flux:input label="Nombre" wire:model="name" placeholder="Ej. Juan Pérez"/>
                    <flux:input label="Correo" wire:model="email" type="email" placeholder="correo@ejemplo.com"/>

                    <div class="flex">
                        <flux:spacer/>
                        <flux:button type="submit" variant="primary">Registrar vendedor</flux:button>
                    </div>
                </form>
            </flux:modal>

        </div>

        @if(session('success'))
            <div
                class="fixed inset-0 flex items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end z-50">
                <div
                    class="max-w-sm w-full bg-green-50 shadow-lg rounded-lg pointer-events-auto ring-1 ring-green-100 overflow-hidden transition-all duration-300 ease-in-out transform hover:scale-[1.02]">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-semibold text-green-800">{{ session('success') }}</p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button
                                    onclick="this.parentElement.parentElement.parentElement.parentElement.remove()"
                                    class="bg-red-50 rounded-md inline-flex text-green-400 hover:text-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <span class="sr-only">Cerrar</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                         fill="currentColor">
                                        <path fill-rule="evenodd"
                                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="relative mt-8 flex flex-col w-full h-auto text-gray-700 bg-white shadow-md rounded-xl bg-clip-border">
        <div class="relative mx-4 mt-4 overflow-hidden text-gray-700 bg-white rounded-none bg-clip-border">
            <div class="flex flex-col justify-between gap-8 mb-4 md:flex-row md:items-center">
                <div class="mt-4">
                    <h5 class="block font-sans text-xl antialiased font-semibold leading-snug tracking-normal text-blue-gray-900">
                        Vendedores registrados
                    </h5>
                    <p class="block mt-1 font-sans text-base antialiased font-normal leading-relaxed text-gray-700">
                        Estos son los vendedores registrados en el sistema.
                    </p>
                </div>
            </div>
        </div>

        <!-- Contenedor con desplazamiento horizontal -->
        <div class="p-6 overflow-x-auto w-full">
            <table class="w-full min-w-[640px] text-left table-auto">
                <thead>
                <tr>
                    <th class="p-4 border-y border-blue-gray-100 bg-blue-gray-50/50">
                        <p class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Nombre
                        </p>
                    </th>
                    <th class="p-4 border-y border-blue-gray-100 bg-blue-gray-50/50">
                        <p class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Correo
                        </p>
                    </th>

                    <th class="p-4 border-y border-blue-gray-100 bg-blue-gray-50/50">
                        <p class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Estado
                        </p>
                    </th>
                    <th class="p-4 border-y border-blue-gray-100 bg-blue-gray-50/50">
                        <p class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                            Registrado
                        </p>
                    </th>
                    <th class="p-4 border-y border-blue-gray-100 bg-blue-gray-50/50">
                        <p class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                        </p>
                    </th>
                </tr>
                </thead>
                <tbody>

                @if(false)
                    <div class="bg-white rounded-lg overflow-hidden">
                        <div class="p-4 text-center">
                            <h3 class="text-lg font-semibold text-gray-900">No hay vendedores registrados</h3>
                            <p class="text-sm text-gray-600">Agrega nuevos vendedores en el sistema.</p>
                        </div>
                    </div>
                @endif

                @forelse($users as $user)
                    <tr>
                        <td class="p-4 border-b border-blue-gray-50">
                            <div class="flex items-center gap-3">
                                <!--
                                <img src=" {{ asset('img/avatar_student.jpg') }}" alt="user profile"
                                     class="relative inline-block h-12 w-12 !rounded-full border border-blue-gray-50 bg-blue-gray-50/50 object-contain object-center p-1"/>
-->
                                <p class="block font-sans text-sm antialiased font-bold leading-normal text-blue-gray-900">
                                    {{ $user->name }}
                                </p>
                            </div>
                        </td>
                        <td class="p-4 border-b border-blue-gray-50">
                            <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900">
                                {{ $user->email }}
                            </p>
                        </td>

                        <td class="p-4 border-b border-blue-gray-50">
                            <flux:field variant="inline">
                                <flux:label for="status">
                                    {{ $status ? 'Activo' : 'Inactivo' }}
                                </flux:label>

                                <flux:switch id="status" wire:model.live="status" />

                                <flux:error name="status" />
                            </flux:field>

                        </td>
                        <td class="p-4 border-b border-blue-gray-50">
                            <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900">
                                {{ $user->created_at->diffForHumans() }}
                            </p>
                        </td>
                        <td class="p-4 border-b border-blue-gray-50">
                            <flux:modal.trigger :name="'delete-vendor-'.$user->id">
                                <flux:button variant="danger">Eliminar</flux:button>
                            </flux:modal.trigger>

                            <flux:modal :name="'delete-vendor-'.$user->id" class="min-w-[22rem]">

                                <input type="hidden" value="$user->id" wire:model="id">
                                <div class="space-y-6">
                                    <div>
                                        <flux:heading size="lg">Quieres eliminar este vendedor?</flux:heading>

                                        <flux:text class="mt-2">
                                            <p>Estas a punto de eliminar este usuario.</p>
                                            <p>Esta accion no se puede deshacer.</p>
                                        </flux:text>
                                    </div>

                                    <div class="flex gap-2">
                                        <flux:spacer/>

                                        <flux:modal.close>
                                            <flux:button variant="ghost">Cancelar</flux:button>
                                        </flux:modal.close>

                                        <flux:button type="submit" variant="danger"
                                                     wire:click="delete({{ $user->id }})">Sí, eliminar vendedor
                                        </flux:button>
                                    </div>
                                </div>
                            </flux:modal>
                        </td>
                    </tr>
                @empty
                @endforelse

                </tbody>
            </table>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
