<div>
    <div class=" sm:px-6 lg:px-8">

        <div class="bg-white p-8 rounded-3xl">
            <h1 class="font-semibold text-2xl">Tus clientes</h1>
            <p class="text-gray-500">Gestiona tus clientes desde aqui.</p>
        </div>
 
        <div class="mb-4 mt-8 flex flex-row gap-4 items-center">

            <livewire:vendor.clients.create @client-created="$refresh"/> <!-- evento de livewire -->

            <x-error-info-message class="me-3 text-red-500" on="error-occurred">
                Error al eliminar el <strong x-text="params"></strong>.
            </x-error-info-message>


           <x-success-info-message class="me-3 text-green-500" on="client-created">
            Cliente <strong x-text="params"></strong> agregado correctamente.
        </x-success-info-message>

        </div>
    </div>

<div
    class="relative mt-8 flex flex-col w-full h-auto text-gray-700 bg-white rounded-3xl bg-clip-border">
    <div class="relative mx-4 mt-4 overflow-hidden text-gray-700 bg-white rounded-none bg-clip-border">
        <div class="flex flex-col justify-between gap-8 mb-4 md:flex-row md:items-center">
            <div>
                <h5 class="block font-sans text-xl antialiased font-semibold leading-snug tracking-normal text-blue-gray-900">
                    Tus clientes
                </h5>
                <p class="block mt-1 font-sans text-base antialiased font-normal leading-relaxed text-gray-700">
                    Estos son tus clientes.
                </p>
            </div>
        </div>
    </div>

    <!-- tabla de clientes -->
    <x-vendor.clients.client-table :clients="$clients"/>
</div>

</div>
