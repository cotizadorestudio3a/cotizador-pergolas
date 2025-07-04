<?php

namespace App\Livewire\Vendor\Quotes;

use App\Models\Services;
use App\Models\ServiceVariants;
use Livewire\Component;

class Index extends Component
{
    public int    $step           = 1;          // 1 = servicio-color, 2 = variante
    public ?int   $selectedService = null;
    public array  $selectedColor   = [];        // [service_id => color]
    public ?int   $selectedVariant = null;      // id de la variante
    public $variants;
    public array $service = [];

    public function irPasoSiguiente()
    {
        if ($this->step === 1) {
            // Validar servicio y color
            $serviceId = $this->selectedService;
            $color     = $this->selectedColor[$serviceId] ?? null;

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
                "variant_id" => $this->selectedVariant
            ];
            
            // Créale la cotización o redirígelo al siguiente formulario.

            $this->step = 3;
            $this->dispatch('irPasoSiguiente', $this->service);
        }

        if($this->step === 3)
        {
        }
    }

    public function render()
    {
        $services = Services::all();
        $variants = $this->variants;
        return view('livewire.vendor.quotes.index', compact('services', 'variants'));
    }
}
