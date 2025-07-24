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
                        <p class="text-3xl font-bold text-gray-800">{{ auth()->user()->clients()->count() }}</p>
                        <p class="text-gray-600">Tus clientes</p>
                    </div>
                </div>
                <div class=" p-6 flex items-center space-x-4">
                    <div class="bg-gray-100 rounded-full p-4">

                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 28 28"><path stroke-width="1" fill="currentColor" d="M3 5.75A2.75 2.75 0 0 1 5.75 3h11.5A2.75 2.75 0 0 1 20 5.75V17h5v4.25A3.75 3.75 0 0 1 21.25 25H6.75A3.75 3.75 0 0 1 3 21.25zM20 23.5h1.25a2.25 2.25 0 0 0 2.25-2.25V18.5H20zM5.75 4.5c-.69 0-1.25.56-1.25 1.25v15.5a2.25 2.25 0 0 0 2.25 2.25H18.5V5.75c0-.69-.56-1.25-1.25-1.25zm2 3.5a.75.75 0 0 0 0 1.5h7.5a.75.75 0 0 0 0-1.5zM7 13.75a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5a.75.75 0 0 1-.75-.75M7.75 18a.75.75 0 0 0 0 1.5h3.5a.75.75 0 0 0 0-1.5z" class="lucide lucide-user-round-icon lucide-user-round"/></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-800"> {{ auth()->user()->quotations()->count() }} </p>
                        <p class="text-gray-600">Cotizaciones realizadas</p>
                    </div>
                </div>

                <div class="p-6 flex items-center space-x-4">
                    <div class="bg-gray-100 rounded-full p-4">
                        <!-- Ícono de dinero -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" color="currentColor"><path d="M20.943 16.835a15.76 15.76 0 0 0-4.476-8.616c-.517-.503-.775-.754-1.346-.986C14.55 7 14.059 7 13.078 7h-2.156c-.981 0-1.472 0-2.043.233c-.57.232-.83.483-1.346.986a15.76 15.76 0 0 0-4.476 8.616C2.57 19.773 5.28 22 8.308 22h7.384c3.029 0 5.74-2.227 5.25-5.165"/><path d="M7.257 4.443c-.207-.3-.506-.708.112-.8c.635-.096 1.294.338 1.94.33c.583-.009.88-.268 1.2-.638C10.845 2.946 11.365 2 12 2s1.155.946 1.491 1.335c.32.37.617.63 1.2.637c.646.01 1.305-.425 1.94-.33c.618.093.319.5.112.8l-.932 1.359c-.4.58-.599.87-1.017 1.035S13.837 7 12.758 7h-1.516c-1.08 0-1.619 0-2.036-.164S8.589 6.38 8.189 5.8zm6.37 8.476c-.216-.799-1.317-1.519-2.638-.98s-1.53 2.272.467 2.457c.904.083 1.492-.097 2.031.412c.54.508.64 1.923-.739 2.304c-1.377.381-2.742-.214-2.89-1.06m1.984-5.06v.761m0 5.476v.764"/></g></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-gray-800">{{ number_format($quotationTotalAmount, 2,) }}</p>
                        <p class="text-gray-600">Total en cotizaciones</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="relative mt-8 flex flex-col w-full h-auto text-gray-700 bg-white rounded-3xl bg-clip-border">

            <!-- Tabla de clientes -->
            <div class="overflow-x-auto p-6">
                <h2 class="text-2xl font-semibold mb-4">Clientes recientes</h2>
                <p class="text-gray-500 mb-6">Aquí puedes ver y gestionar tus clientes.</p>
                <flux:button variant="primary" class="mb-4" href="{{ route('vendor.clients.index') }}">
                    Mis clientes
                </flux:button>

                @if ($clients->isEmpty())
                    <p class="text-gray-500">No tienes clientes registrados.</p>
                @else
                    <x-vendor.clients.client-table :clients="$clients" />
                @endif
            </div>
        </div>
    </div>
