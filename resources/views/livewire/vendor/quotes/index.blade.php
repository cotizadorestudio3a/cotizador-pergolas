<div>
    <div class=" sm:px-6 lg:px-8">

        <div class="bg-white p-8 rounded-3xl">
            <h1 class="font-semibold text-2xl">Nueva Cotización</h1>
            <p class="text-gray-500">Ingresa los datos de la cotización.</p>
        </div>

        <div class="flex items-center justify-end mt-4">
            <!-- Paso 1 -->
            <div class="relative flex items-center">
                <div
                    class="w-10 h-10 rounded-full flex items-center justify-center
                        {{ $step == 1 ? 'bg-gray-800' : 'bg-slate-400' }}
                ">
                    <span class="text-white text-xl font-bold">1</span>
                </div>
            </div>

            <!-- Línea conectora 1-2 -->
            <div class="h-0.5 w-12 bg-gray-300"></div>

            <!-- Paso 2  -->
            <div class="relative flex items-center">
                <div
                    class="w-10 h-10 rounded-full flex items-center justify-center
                        {{ $step == 2 ? 'bg-gray-800' : 'bg-slate-400' }}
                ">
                    <span class="text-white text-xl font-bold">2</span>
                </div>
            </div>

            <!-- Línea conectora 2-3 -->
            <div class="h-0.5 w-12 bg-gray-300"></div>

            <!-- Paso 3 -->
            <div class="relative flex items-center">
                <div
                    class="w-10 h-10 rounded-full flex items-center justify-center
                        {{ $step == 3 ? 'bg-gray-800' : 'bg-slate-400' }}
                ">
                    <span class="text-white text-xl font-bold">3</span>
                </div>
            </div>

            <!-- Línea conectora 3-4 -->
            <div class="h-0.5 w-12 bg-gray-300"></div>

            <!-- Paso 4 -->
            <div class="relative flex items-center">
                <div
                    class="w-10 h-10 rounded-full flex items-center justify-center
                        {{ $step == 4 ? 'bg-gray-800' : 'bg-slate-400' }}
                ">
                    <span class="text-white text-xl font-bold">4</span>
                </div>
            </div>

        </div>
        <div class="px-6 py-10 bg-white mt-6 rounded-2xl">

            @if ($step === 1)
                <x-vendor.quotes.select-service :services="$services" :selectedService="$selectedService" />
            @endif

            @if ($step === 2)
                <x-vendor.quotes.select-service-variant :variants="$variants" :selectedVariant="$selectedVariant" />
            @endif


            @if ($step === 3)
                <x-vendor.quotes.calculator-form-service 
                :clients="$clients" 
                :selectedCuadricula="$selectedCuadricula" 
                :available_services="$available_services"
                :available_variants="$available_variants" 
                :pvp="$pvp" 
                :iva="$iva" 
                :total="$total" 
                :added_services="$added_services" 
                :activeServiceIndex="$activeServiceIndex" />
            @endif

            @if ($step === 4)
                <x-vendor.quotes.generate-pdf-files :pdfs_generados="$pdfs_generados" />
            @endif

            <!-- Modal para agregar otro servicio -->
            <flux:modal name="add-service-modal" class="min-w-[28rem]">
                <div class="px-6 py-10 bg-white rounded-xl">
                    <!-- Indicador de pasos interno opcional -->
                    @if ($newServiceStep === 1)
                        <x-vendor.quotes.modals.select-service :services="$services" :selectedService="$selectedService" />
                        <div class="flex justify-end mt-6 sticky bottom-0">
                            <flux:button wire:click="newServiceNextStep" variant="primary">Siguiente</flux:button>
                        </div>
                    @endif

                    @if ($newServiceStep === 2)
                        <x-vendor.quotes.modals.select-service-variant :variants="$variants" :selectedVariant="$selectedVariant" />
                        <div class="flex justify-between sticky bottom-0 bg-white p-6">
                            <flux:button wire:click="$set('newServiceStep', 1)" variant="ghost">Atrás</flux:button>
                            <flux:button wire:click="confirmAddService" variant="primary">Agregar servicio</flux:button>
                        </div>
                    @endif
                </div>
            </flux:modal>

        </div>
    </div>
