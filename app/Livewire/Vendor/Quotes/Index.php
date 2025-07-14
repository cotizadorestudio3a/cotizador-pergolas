<?php

namespace App\Livewire\Vendor\Quotes;

use App\Models\Services;
use App\Models\ServiceVariants;
use App\Services\Cuadriculas\CuadriculaFactory;
use Livewire\Component;
use App\Services\Pergolas\PergolaFactory;

class Index extends Component
{
    public int $step = 1;
    public int $newServiceStep = 1;
    public ?int $selectedService = 1; // id de la pergola o servicio
    public ?string $selectedColor;
    public ?int $selectedVariant = 1;      // id de la variante
    public ?string $selectedCuadricula;
    public $available_services;
    public $available_variants;
    public $pergola_inputs = [];
    public $variants;
    public $pvp = 0;
    public $iva = 0;
    public $total = 0;
    public array $added_services = []; // los servicios que se agregan
    public $medidaA;
    public $medidaB;
    public $alto;
    public $n_columnas;
    public $n_bajantes;
    public $anillos;
    public $client_id;
    public array $pdfs_generados = [];
    public $tipos_cuadricula = ['cuadricula', 'cuadricula_trama'];
    public int $activeServiceIndex = 0;


    // Inputs cuadricula
    public $medidaACuadricula;
    public $medidaBCuadricula;
    public $distanciaPalillajeCuadricula;
    public $altoCuadricula;


    public function irPasoSiguiente()
    {
        if ($this->step === 1) {
            $this->validate([
                'selectedColor' => 'required'
            ], [
                'selectedColor' => 'Por favor, seleccione un color.'
            ]);

            $serviceId = $this->selectedService;
            $color = $this->selectedColor ?? null;

            // Cargar variantes del servicio elegido
            $this->variants = ServiceVariants::where('service_id', $serviceId)->get();
            $this->step = 2;          // Avanzar al paso 2
            return;
        }

        if ($this->step === 2) {

            $this->validate([
                'selectedVariant' => 'required',
                'selectedCuadricula' => 'required'
            ], [
                'selectedVariant' => 'Por favor, seleccione una variante.',
                'selectedCuadricula' => 'Por favor, seleccione una cuadrícula.'
            ]);

            $this->added_services[] = [
                "service_id" => $this->selectedService,
                "color" => $this->selectedColor,
                "variant_id" => $this->selectedVariant,
                "selected_cuadricula" => $this->selectedCuadricula
            ];

            $this->step = 3;
            $this->dispatch('irPasoSiguiente', $this->added_services);
        }
    }

    public function calcularTotal()
    {
        $this->validatePergolaInputs();

        $this->pergola_inputs = $this->getPergolaInputs();
        $pergola = PergolaFactory::crear($this->selectedService, $this->pergola_inputs);
        $total_pergola = $pergola->calcular();

        if ($this->selectedCuadricula === 'cuadricula') {
            $this->validateCuadriculaInputs();
            $cuadricula = CuadriculaFactory::crear($this->selectedCuadricula, $this->getInputsCuadricula());
            $total_cuadricula = $cuadricula->calcular();
        } elseif ($this->selectedCuadricula === 'cuadricula_trama') {
            $this->validateCuadriculaInputs();
            $cuadricula = CuadriculaFactory::crear($this->selectedCuadricula, $this->getInputsCuadricula());
            $total_cuadricula = $cuadricula->calcular();
        }

        // Cálculo del PVP total (pérgola + cuadrícula (si la hay ), sin IVA)
        $pvp_total = $total_pergola['pvp_pergola'] + ($total_cuadricula['pvp_cuadricula'] ?? 0);

        $iva_total = $pvp_total * 0.15;

        $total = $pvp_total + $iva_total;

        $this->pvp = round($pvp_total);
        $this->iva = $iva_total;
        $this->total = $total;

    }


    public function generatePDFFiles()
    {
        $this->step = 4;

        // PDF de la pérgola
        $pergola = PergolaFactory::crear($this->selectedService, $this->pergola_inputs);
        $pergola->calcular();
        $this->pdfs_generados[] = [
            'titulo' => 'Orden Producción Pérgola',
            'path' => $pergola->obtenerPDFOrdenProduccion(),
        ];

        // PDF de la cuadrícula (si existe)
        if (in_array($this->selectedCuadricula, $this->tipos_cuadricula)) {
            $cuadricula = CuadriculaFactory::crear($this->selectedCuadricula, $this->getInputsCuadricula());
            $cuadricula->calcular();
            $this->pdfs_generados[] = [
                'titulo' => 'Orden Producción Cuadrícula',
                'path' => $cuadricula->obtenerPDFOrdenProduccion(),
            ];
        }

        // Aquí puedes agregar más tipos de PDFs si agregas más servicios.
    }


    private function getPergolaInputs()
    {
        return [
            "medidaA" => $this->medidaA,
            "medidaB" => $this->medidaB,
            "alto" => $this->alto,
            "n_columnas" => $this->n_columnas,
            "n_bajantes" => $this->n_bajantes,
            "anillos" => $this->anillos
        ];
    }

    private function getInputsCuadricula()
    {
        return [
            "medidaA" => $this->medidaACuadricula,
            "medidaB" => $this->medidaBCuadricula,
            "distanciaPalillaje" => $this->distanciaPalillajeCuadricula,
            "alto" => $this->altoCuadricula
        ];
    }

    public function decrementStep()
    {
        if ($this->step > 1) {
            $this->step--;
            $this->added_services = [];
            $this->reset('selectedCuadricula');
        }
    }

    /**
     * Reinicia el flujo para agregar un nuevo servicio manteniendo los que ya fueron agregados.
     */
    public function startAddService()
    {
        // Iniciar flujo interno del modal
        $this->newServiceStep = 1;

        // Resetear únicamente los campos de selección del nuevo servicio
        $this->reset(
            'selectedService',
            'selectedColor',
            'selectedVariant',
            'selectedCuadricula',
            'variants'
        );
    }

    /**
     * Avanza al siguiente paso dentro del modal de nuevo servicio.
     */
    public function newServiceNextStep()
    {
        if ($this->newServiceStep === 1) {
            $this->validate([
                'selectedColor' => 'required',
                'selectedService' => 'required'
            ], [
                'selectedColor.required' => 'Seleccione un color',
                'selectedService.required' => 'Seleccione un servicio'
            ]);

            $this->variants = ServiceVariants::where('service_id', $this->selectedService)->get();
            $this->newServiceStep = 2;
            return;
        }
    }

    /**
     * Confirma la selección en el modal y agrega el servicio a la lista.
     */
    public function confirmAddService()
    {
        $this->validate([
            'selectedVariant' => 'required',
            'selectedCuadricula' => 'required'
        ]);

        $this->added_services[] = [
            "service_id" => $this->selectedService,
            "color" => $this->selectedColor,
            "variant_id" => $this->selectedVariant,
            "selected_cuadricula" => $this->selectedCuadricula
        ];

        // Establecer el nuevo servicio como el activo
        $this->activeServiceIndex = count($this->added_services) - 1;

        // Reset del modal
        $this->dispatch('modal-close', name: 'add-service-modal');
        $this->newServiceStep = 1;
    }


    private function validatePergolaInputs()
    {
        $this->validate(
            [
                'medidaA' => 'required|numeric',
                'medidaB' => 'required|numeric',
                'alto' => 'required|numeric',
                'n_columnas' => 'required|numeric',
                'n_bajantes' => 'required|numeric',
                'anillos' => 'required|numeric',
                'client_id' => 'required'
            ],
            [
                'medidaA.required' => 'Por favor, ingresa la medida A.',
                'medidaB.required' => 'Por favor, ingresa la medida B.',
                'alto.required' => 'Por favor, ingresa el alto.',
                'n_columnas.required' => 'Por favor, ingresa el número de columnas.',
                'n_bajantes.required' => 'Por favor, ingresa el número de bajantes.',
                'anillos.required' => 'Por favor, ingresa el número de anillos.',
                'client_id.required' => 'Por favor, selecciona un cliente.'
            ]
        );
    }

    private function validateCuadriculaInputs()
    {
        $this->validate([
            'medidaACuadricula' => 'required|numeric',
            'medidaBCuadricula' => 'required|numeric',
            'distanciaPalillajeCuadricula' => 'required|numeric',
            'altoCuadricula' => 'required|numeric',
        ], [
            'medidaACuadricula.required' => 'Por favor, ingresa la medida A.',
            'medidaBCuadricula.required' => 'Por favor, ingresa la medida B.',
            'distanciaPalillajeCuadricula.required' => 'Por favor, ingresa la distancia de palillaje.',
            'altoCuadricula.required' => 'Por favor, ingresa el alto.',
        ]);
    }
    public function mount()
    {
        $this->available_services = Services::all(['id', 'name']);
        $this->available_variants = ServiceVariants::all(['id', 'name']);
    }


    public function render()
    {
        $services = Services::all();
        $variants = $this->variants;
        $clients = auth()->user()->clients;
        $added_services = $this->added_services;
        return view('livewire.vendor.quotes.index', compact('services', 'variants', 'clients', 'added_services'));
    }
}
