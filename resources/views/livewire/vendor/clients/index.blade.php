<div>
  <div class="sm:px-6 lg:px-8">

        <div class="bg-white p-8 rounded-3xl">
            <h1 class="font-semibold text-2xl">Mis clientes</h1>
            <p class="text-gray-500">Gestiona tus clientes desde aquí.</p>
        </div>

        <div class="mb-4 mt-8 flex flex-row gap-4 items-center">

            <livewire:vendor.clients.create @client-created="$refresh" /> <!-- evento de livewire -->

            <x-error-info-message class="me-3 text-red-500" on="error-occurred">
                Error al eliminar el <strong x-text="params"></strong>.
            </x-error-info-message>


            <x-success-info-message class="me-3 text-green-500" on="client-created">
                Cliente <strong x-text="params"></strong> agregado correctamente.
            </x-success-info-message>

        </div>

        <!-- tabla de clientes -->
        <x-vendor.clients.client-table :clients="$clients" />

    </div>
</div>
