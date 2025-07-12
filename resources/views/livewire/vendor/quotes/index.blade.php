<div>
    <div class=" sm:px-6 lg:px-8">

        <div class="bg-white p-8 rounded-3xl">
            <h1 class="font-semibold text-2xl">Nueva Cotización</h1>
            <p class="text-gray-500">Ingresa los datos de la cotización.</p>
        </div>

        <div class="flex items-center justify-end mt-4">
            <!-- Paso 1 -->
            <div class="relative flex items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center
                        {{ $step == 1 ? 'bg-gray-800' : 'bg-slate-400' }}
                ">
                    <span class="text-white text-xl font-bold">1</span>
                </div>
            </div>

            <!-- Línea conectora 1-2 -->
            <div class="h-0.5 w-12 bg-gray-300"></div>

            <!-- Paso 2  -->
            <div class="relative flex items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center
                        {{ $step == 2 ? 'bg-gray-800' : 'bg-slate-400' }}
                ">
                    <span class="text-white text-xl font-bold">2</span>
                </div>
            </div>

            <!-- Línea conectora 2-3 -->
            <div class="h-0.5 w-12 bg-gray-300"></div>

            <!-- Paso 3 -->
            <div class="relative flex items-center">
                <div class="w-10 h-10 rounded-full flex items-center justify-center
                        {{ $step == 3 ? 'bg-gray-800' : 'bg-slate-400' }}
                ">
                    <span class="text-white text-xl font-bold">3</span>
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
                />
            @endif

            @if ($step === 4)
                <x-vendor.quotes.generate-pdf-files 
                :pdf_orden_produccion="$pdf_orden_produccion"
                />
            @endif

        </div>
    </div>
