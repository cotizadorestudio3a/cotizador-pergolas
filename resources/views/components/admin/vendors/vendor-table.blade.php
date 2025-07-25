<div class="p-6 overflow-x-auto w-full">
    <table class="w-full min-w-[640px] text-left table-auto">
        <thead>
            <tr>
                <th class="p-4 border-y border-blue-gray-100 bg-blue-gray-50/50">
                    <p
                        class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                        Nombre
                    </p>
                </th>
                <th class="p-4 border-y border-blue-gray-100 bg-blue-gray-50/50">
                    <p
                        class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                        Correo
                    </p>
                </th>

                <th class="p-4 border-y border-blue-gray-100 bg-blue-gray-50/50">
                    <p
                        class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                        Registrado
                    </p>
                </th>
                <th class="p-4 border-y border-blue-gray-100 bg-blue-gray-50/50">
                    <p
                        class="block font-sans text-sm antialiased font-normal leading-none text-blue-gray-900 opacity-70">
                    </p>
                </th>
            </tr>
        </thead>
        <tbody>

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
                        <p class="block font-sans text-sm antialiased font-normal leading-normal text-blue-gray-900">
                            {{ $user->created_at->diffForHumans() }}
                        </p>
                    </td>
                            @if (!request()->routeIs('admin.dashboard'))
                        <td class="p-4 border-b border-blue-gray-50">
                            <div x-data="{ confirming: false }" class="relative">

                                <!-- Botón principal -->
                                <flux:button variant="danger" @click="confirming = !confirming">Borrar</flux:button>


                                <!-- Popover flotante -->
                                <div x-show="confirming" x-transition @click.outside="confirming = false"
                                    class="absolute z-10 mt-2 left-0 w-48 bg-white shadow-lg rounded-lg border border-gray-200 p-4 flex flex-col gap-3">
                                    <p class="text-sm text-gray-700">¿Seguro que quieres eliminar este vendedor?</p>

                                    <div class="flex flex-col gap-2">
                                        <flux:button variant="ghost" @click="confirming = false">Cancelar</flux:button>
                                        <flux:button variant="danger" wire:click="delete({{ $user->id }})"
                                            @click="confirming = false">
                                            Sí, eliminar
                                        </flux:button>
                                    </div>
                                </div>
                    @endif
</div>
</td>

</tr>
@empty
<div class="bg-white rounded-lg overflow-hidden">
    <div class="p-4 text-center">
        <h3 class="text-lg font-semibold text-gray-900">No hay vendedores registrados</h3>
        <p class="text-sm text-gray-600">Agrega nuevos vendedores en el sistema.</p>
    </div>
</div>
@endforelse

</tbody>
</table>

@if (method_exists($users, 'links'))
    <div class="mt-4">
        {{ $users->links() }}
    </div>
@endif
</div>
