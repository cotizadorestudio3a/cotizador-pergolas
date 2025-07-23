<div>
    <div>

    <!-- Header -->
    <div class="bg-white p-8 rounded-3xl mb-6">
        <h1 class="font-semibold text-2xl">Gestión de vendedores</h1>
        <p class="text-gray-500">Gestiona todos los vendedores que se han registrado en el sistema</p>
    </div>
 
        <div class="mb-4 mt-8 flex flex-row gap-4 items-center">
            <livewire:admin.vendors.create @vendor-created="$refresh"/>
            <x-error-info-message class="me-3 text-red-500" on="error-occurred">
                Error al eliminar el <strong x-text="params"></strong>.
            </x-error-info-message>


         <x-success-info-message class="me-3 text-green-500" on="vendor-deleted">
            Vendedor <strong x-text="params"></strong> eliminado correctamente.
        </x-success-info-message>

        </div>
    </div>

<div
    class="relative mt-8 flex flex-col w-full h-auto text-gray-700 bg-white rounded-3xl bg-clip-border">
    <div class="relative mx-4 mt-4 overflow-hidden text-gray-700 bg-white rounded-none bg-clip-border">
        <div class="flex flex-col justify-between gap-8 mb-4 md:flex-row md:items-center">
            <div>
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
    <x-admin.vendors.vendor-table :users="$users"/>
</div>

</div>
