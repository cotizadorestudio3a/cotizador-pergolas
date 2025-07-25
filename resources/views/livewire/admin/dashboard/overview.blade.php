<div class="sm:px-6 lg:px-8">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <p class="font-normal text-xl">Hola, <span class="font-medium"> {{ auth()->user()->name }} </span> 👋🏻</p>
        <div class="grid grid-cols-1 gap-4 mt-8 bg-white rounded-3xl mb-16 md:grid-cols-2 lg:grid-cols-3">
            <div class=" p-6 flex items-center space-x-4">
                <div class="bg-gray-100 rounded-full p-4">
                    <!-- Ícono de usuarios -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" d="M6.578 15.482c-1.415.842-5.125 2.562-2.865 4.715C4.816 21.248 6.045 22 7.59 22h8.818c1.546 0 2.775-.752 3.878-1.803c2.26-2.153-1.45-3.873-2.865-4.715a10.66 10.66 0 0 0-10.844 0M16.5 6.5a4.5 4.5 0 1 1-9 0a4.5 4.5 0 0 1 9 0" color="currentColor" class="lucide lucide-user-round-icon lucide-user-round"/></svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-800">{{ $clientCount }}</p>
                    <p class="text-gray-600">Clientes registrados</p>
                </div>
            </div>
            <div class=" p-6 flex items-center space-x-4">
                <div class="bg-gray-100 rounded-full p-4">
                    <!-- Ícono de vendedores -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 48 48"><path stroke-width="2.3" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M15.463 24.09h6.156M12.5 19.112h10.218M35.5 24.007a4.892 4.892 0 0 1-9.784-.004a4.89 4.89 0 0 1 4.892-4.892a4.89 4.89 0 0 1 4.888 4.892z"/><rect width="37" height="37" x="5.5" y="5.5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2.3" stroke-linejoin="round" rx="4" ry="4"/></svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-800">{{ $vendorsCount }}</p>
                    <p class="text-gray-600">Vendedores registrados</p>
                </div>
            </div>

            <div class="p-6 flex items-center space-x-4">
                <div class="bg-gray-100 rounded-full p-4">
                    <!-- Ícono de cotizaciones -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 28 28"><path stroke-width="1" fill="currentColor" d="M3 5.75A2.75 2.75 0 0 1 5.75 3h11.5A2.75 2.75 0 0 1 20 5.75V17h5v4.25A3.75 3.75 0 0 1 21.25 25H6.75A3.75 3.75 0 0 1 3 21.25zM20 23.5h1.25a2.25 2.25 0 0 0 2.25-2.25V18.5H20zM5.75 4.5c-.69 0-1.25.56-1.25 1.25v15.5a2.25 2.25 0 0 0 2.25 2.25H18.5V5.75c0-.69-.56-1.25-1.25-1.25zm2 3.5a.75.75 0 0 0 0 1.5h7.5a.75.75 0 0 0 0-1.5zM7 13.75a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5a.75.75 0 0 1-.75-.75M7.75 18a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5z" class="lucide lucide-user-round-icon lucide-user-round"/></svg>
                </div>
                <div>
                    <p class="text-3xl font-bold text-gray-800">{{ $quotationsCount }}</p>
                    <p class="text-gray-600">Cotizaciones realizadas</p>
                </div>
            </div>
        </div>

    </div>

    <div class="relative mt-8 flex flex-col w-full h-auto text-gray-700 bg-white rounded-3xl bg-clip-border">

        <!-- Tabla de vendedores -->
        <div class="overflow-x-auto p-6">
            <h2 class="text-2xl font-semibold mb-4">Vendedores recientes</h2>
            <p class="text-gray-500 mb-6">Aquí puedes ver y gestionar los vendedores del sistema.</p>
            <flux:button variant="primary" class="mb-4" href="{{ route('admin.vendors.index') }}">
                Gestionar vendedores
            </flux:button>

            @if ($users->isEmpty())
                <p class="text-gray-500">No hay vendedores registrados.</p>
            @else
                <x-admin.vendors.vendor-table :users="$users" />
            @endif
        </div>
    </div>
</div>
