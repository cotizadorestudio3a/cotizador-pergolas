<?php

namespace App\Livewire\Vendor\Quotes;

use App\Models\Services;
use App\Models\ServiceVariants;
use App\Services\Cuadriculas\CuadriculaFactory;
use Livewire\Component;
use App\Services\Pergolas\PergolaFactory;

class Index extends Component
{
    public int $step = 3;          // 1 = servicio-color, 2 = variante
    public ?int $selectedService = null;
    public array $selectedColor = [];        // [service_id => color]
    public ?int $selectedVariant = null;      // id de la variante
    public ?string $selectedCuadricula = 'cuadricula';
    public $variants;
    public $pvp = 0;
    public $iva = 0;
    public $total = 0;
    public array $service = [];
    public $medidaA;
    public $medidaB;
    public $alto;
    public $n_columnas;
    public $n_bajantes;
    public $anillos;

    // Inputs cuadricula
    public $medidaACuadricula;
    public $medidaBCuadricula;
    public $distanciaPalillajeCuadricula;
    public $altoCuadricula;


    public function irPasoSiguiente()
    {
        if ($this->step === 1) {
            // Validar servicio y color
            $serviceId = $this->selectedService;
            $color = $this->selectedColor[$serviceId] ?? null;

            if (!$serviceId || !$color) {
                $this->dispatch('error', message: 'Elige un servicio y un color.');
                return;
            }
            // Cargar variantes del servicio elegido
            $this->variants = ServiceVariants::where('service_id', $serviceId)->get();
            $this->step = 2;          // Avanzar al paso 2
            return;
        }

        if ($this->step === 2) {
            if (!$this->selectedVariant) {
                $this->dispatch('error', message: 'Selecciona una variante.');
                return;
            }

            $this->service = [
                "service_id" => $this->selectedService,
                "color" => $this->selectedColor[$this->selectedService],
                "variant_id" => $this->selectedVariant,
                "selected_cuadricula" => $this->selectedCuadricula
            ];

            // Créa cotización o redirígelo al siguiente formulario.
            $this->step = 3;
            $this->dispatch('irPasoSiguiente', $this->service);
        }

        if ($this->step === 3) {
        }
    }

    public function calcularTotal()
    {
        $this->selectedService = 1; 
        $pergola = PergolaFactory::crear($this->selectedService, $this->getInputs());
        $total_pergola = $pergola->calcular();

        if ($this->selectedCuadricula === 'cuadricula') {
            $cuadricula = CuadriculaFactory::crear($this->selectedCuadricula, $this->getInputsCuadricula());
            $total_cuadricula = $cuadricula->calcular();
        } else {
            $total_cuadricula = $this->calcularCuadriculaTrama();
        }

        // Cálculo del PVP total (pérgola + cuadrícula, sin IVA)
        $pvp_total = $total_pergola['pvp_pergola'] + $total_cuadricula['pvp_cuadricula'];

        // Cálculo del IVA (15% del PVP total)
        $iva_total = $pvp_total * 0.15;

        // Cálculo del total (PVP total + IVA)
        $total = $pvp_total + $iva_total;

        // Asignación a las propiedades
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


    public function render()
    {
        $services = Services::all();
        $variants = $this->variants;
        $clients = auth()->user()->clients;
        return view('livewire.vendor.quotes.index', compact('services', 'variants', 'clients'));
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
