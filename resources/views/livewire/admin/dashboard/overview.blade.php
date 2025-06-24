<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <p class="font-normal text-xl">Hola, <span class="font-medium"> {{ auth()->user()->name }} </span> 👋🏻</p>
        <div class="grid grid-cols-1 gap-4 mt-8 bg-white rounded-3xl mb-16 md:grid-cols-2 lg:grid-cols-3">
            <div class=" p-6 flex items-center space-x-4">
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
            <div class=" p-6 flex items-center space-x-4">
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

            <div class="p-6 flex items-center space-x-4">
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

    </div>

    <div class="relative mt-8 flex flex-col w-full h-auto text-gray-700 bg-white rounded-3xl bg-clip-border">

        <!-- Contenedor con desplazamiento horizontal -->
        <x-admin.vendors.vendor-table :users="$users" />
    </div>
</div>
