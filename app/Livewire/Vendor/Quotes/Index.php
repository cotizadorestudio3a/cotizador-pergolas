<?php

namespace App\Livewire\Vendor\Quotes;

use App\Models\Services;
use App\Models\ServiceVariants;
use App\Services\Cuadriculas\CuadriculaFactory;
use Livewire\Component;
use App\Services\Pergolas\PergolaFactory;
use Livewire\Attributes\Validate;

class Index extends Component
{
    public int $step = 1;
    public ?int $selectedService = 1; // id de la pergola o servicio
    public ?string $selectedColor;
    public ?int $selectedVariant = 1;      // id de la variante
    public ?string $selectedCuadricula;
    public $available_services;
    public $available_variants;
    public $variants;
    public $pvp = 0;
    public $iva = 0;
    public $total = 0;
    public array $added_services = [];
    public $medidaA;
    public $medidaB;
    public $alto;
    public $n_columnas;
    public $n_bajantes;
    public $anillos;
    public $client_id;

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
        //empty($this->selectedCuadricula) ? $this->validateCuadriculaInputs() : $this->validateCuadriculaTramaInputs();

        $pergola = PergolaFactory::crear($this->selectedService, $this->getInputs());
        $total_pergola = $pergola->calcular();

        if ($this->selectedCuadricula === 'cuadricula') {
            $this->validateCuadriculaInputs();
            $cuadricula = CuadriculaFactory::crear($this->selectedCuadricula, $this->getInputsCuadricula());
            $total_cuadricula = $cuadricula->calcular();
        } elseif ($this->selectedCuadricula === 'cuadricula_trama') {
            $this->validateCuadriculaTramaInputs();
            $total_cuadricula = $this->calcularCuadriculaTrama();
        }

        // Cálculo del PVP total (pérgola + cuadrícula (si la hay ), sin IVA)
        $pvp_total = $total_pergola['pvp_pergola'] + ($total_cuadricula['pvp_cuadricula'] ?? 0);

        $iva_total = $pvp_total * 0.15;

        $total = $pvp_total + $iva_total;

        $this->pvp = round($pvp_total);
        $this->iva = $iva_total;
        $this->total = $total;
    }

    private function getInputs()
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
        }
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

    /*
    private function calcularCuadriculaTrama()
    {
        $this->numero_cuadriculas = ceil(min($this->medidaACuadriculaTrama, $this->medidaBCuadriculaTrama) / $this->distanciaPalillajeCuadriculaTrama) + 1;
        $this->largo_cuadriculas = max($this->medidaACuadriculaTrama, $this->medidaBCuadriculaTrama);
        $this->vigas_cubierta_cuadricula = ceil(max($this->medidaACuadriculaTrama, $this->medidaBCuadriculaTrama) / 0.55) + 1;
        $this->largo_vigas_cuadricula = ceil(min($this->medidaBCuadriculaTrama, $this->distanciaPalillajeCuadriculaTrama) / 0.55) + 1;
        $this->area_cuadricula = $this->medidaBCuadriculaTrama * $this->medidaACuadriculaTrama;

        $this->total_cuadricula = 10 * ($this->numero_cuadriculas * $this->largo_cuadriculas) / 6.4;
        $this->total_tornillos_cuadricula = 0.06 * $this->numero_cuadriculas * $this->vigas_cubierta_cuadricula;
        $this->total_tornillos_t = 0.06 * ($this->vigas_cubierta_cuadricula * $this->largo_vigas_cuadricula);
        $this->total_t_cuadricula = 5.42 * $this->vigas_cubierta_cuadricula * $this->largo_vigas_cuadricula * 2;
        $this->total_mano_de_obra = 5 * $this->area_cuadricula;
    }
        */
}
