<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid grid-cols-1 gap-4 mt-8 mb-16 md:grid-cols-2 lg:grid-cols-3">
            <div class="bg-white rounded-2xl shadow-md p-6 flex items-center space-x-4">
                <div class="bg-green-100 rounded-full p-4">
                    <!-- Ícono de usuarios -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none"
                         stroke="#00AC4F" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-user-round-icon lucide-user-round">
                        <circle cx="12" cy="8" r="5"/>
                        <path d="M20 21a8 8 0 0 0-16 0"/>
                    </svg>

                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-800">0</p>
                    <p class="text-gray-600">Usuarios registrados</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 flex items-center space-x-4">
                <div class="bg-green-100 rounded-full p-4">

                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none"
                         stroke="#00AC4F" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-user-round-icon lucide-user-round">
                        <circle cx="12" cy="8" r="5"/>
                        <path d="M20 21a8 8 0 0 0-16 0"/>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-800">{{ $vendorsCount }}</p>
                    <p class="text-gray-600">Vendedores registrados</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-md p-6 flex items-center space-x-4">
                <div class="bg-green-100 rounded-full p-4">
                    <!-- Ícono de libros -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none"
                         stroke="#00AC4F" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-user-round-icon lucide-user-round">
                        <circle cx="12" cy="8" r="5"/>
                        <path d="M20 21a8 8 0 0 0-16 0"/>
                    </svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-800">0</p>
                    <p class="text-gray-600">Cotizaciones realizadas</p>
                </div>
            </div>
        </div>
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20"/>
        </div>
    </div>

    <div class="relative mt-8 flex flex-col w-full h-auto text-gray-700 bg-white shadow-md rounded-xl bg-clip-border">

        <!-- Contenedor con desplazamiento horizontal -->
        <div
            class="relative mt-8 flex flex-col w-full h-auto text-gray-700 bg-white shadow-md rounded-xl bg-clip-border">
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
</div>
