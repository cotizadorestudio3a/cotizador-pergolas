<?php

namespace App\Livewire\Vendor\Quotes;

use App\Models\Services;
use App\Models\ServiceVariants;
use Livewire\Component;

class Index extends Component
{
    public int    $step           = 3;          // 1 = servicio-color, 2 = variante
    public ?int   $selectedService = null;
    public array  $selectedColor   = [];        // [service_id => color]
    public ?int   $selectedVariant = null;      // id de la variante
    public ?array $selectedVariantType = [];
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
    public $numeroVigas;
    public $largoVigas;
    public $largoVigaPrincipal;
    public $area;
    public $tiempoDias;
    public $tiempoMeses;
    public $pergola;

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
                "variant_id" => $this->selectedVariant,
                "variant_type" => $this->selectedVariantType
            ];
            
            // Créale la cotización o redirígelo al siguiente formulario.

            $this->step = 3;
            $this->dispatch('irPasoSiguiente', $this->service);
        }

        if($this->step === 3)
        {
            $this->calcularTotal();
        }
    }

    public function calcularTotal()
    {
       /* $this->validate([
            'medidaA' => 'required|numeric',
            'medidaB' => 'required|numeric',
            'alto' => 'required|numeric',
            'n_columnas' => 'required|numeric',
            'n_bajantes' => 'required|numeric',
            'anillos' => 'required|numeric',
        ]);

        */

        $mayor = max($this->medidaA, $this->medidaB);
        $menor = min($this->medidaA, $this->medidaB);

        // CALCULOS INICIALES
        $this->numeroVigas = ceil($mayor / 0.65) + 1;
        $this->largoVigas = $menor;
        $this->largoVigaPrincipal = $mayor;
        $this->area = $this->medidaA * $this->medidaB;
        $this->tiempoDias = $this->area / 1.8;
        $this->tiempoMeses = $this->tiempoDias / 30;

        $aumento_desperdicio_vigas = 1.2; // LLEVA CONDICIONALES //
        $aumento_desperdicio_columnas = 0; // LLEVA CONDICIONALES //


        //CANTIDAD DE CADA ITEM //
        $cantidad_viga_principal_sujecion = $this->largoVigaPrincipal * 3;
        $cantidad_viga_secundaria = ($aumento_desperdicio_vigas + $this->largoVigas) * $this->numeroVigas;
        $cantidad_columna = $this->n_columnas * $this->alto + $aumento_desperdicio_columnas * $this->n_columnas;
        $cantidad_anillo = $this->anillos * (0.45 * 4);
        $cantidad_canal_agua = $this->largoVigaPrincipal; 
        $cantidad_malla = 0.45 * $this->largoVigaPrincipal; 
        $cantidad_alucobond_canal = $this->largoVigaPrincipal* 0.3;
        $cantidad_ancla =   $this->numeroVigas * 0.6 + $this->n_columnas * 0.6;
        $cantidad_tornillos_pared = $this->numeroVigas * 6;
        $cantidad_tornillos_piso = $this->n_columnas * 6;
        $cantidad_tacos = $cantidad_tornillos_pared + $cantidad_tornillos_piso;
        $cantidad_tornillos_aluminio = $this->numeroVigas * 6 + $this->n_columnas * 12;
        $cantidad_fleje_metalico = $this->largoVigaPrincipal;
        $cantidad_aquaprotect = $cantidad_fleje_metalico * 0.1;
        $cantidad_andamios = intval(($this->alto/2) * ($this->area / 15));
        $cantidad_t = $this->numeroVigas * $this->largoVigas; 
        $cantidad_angulo = $this->largoVigaPrincipal;
        $cantidad_cinta_doble_faz = ($this->numeroVigas * $this->largoVigas) * 2;
        $cantidad_silicon_sellante = (($this->numeroVigas * $this->largoVigas) * 2) + $this->largoVigaPrincipal * intval($this->largoVigas / 2.4) + $this->largoVigaPrincipal * 4;
        $cantidad_silicon_color = $this->largoVigaPrincipal * 3 + ($this->n_columnas * 0.6 ) * 3;
        $cantidad_masking = ($cantidad_silicon_color + $cantidad_silicon_sellante) * 2;
        $cantidad_vidrio = $this->area;
        $cantidad_alumband = $this->largoVigaPrincipal * 2;
        $cantidad_tubo_pvc_3 = $this->n_bajantes * $this->alto * 1.2;
        $cantidad_codo_pvc_45_3 = 2;
        $cantidad_codo_pvc_90_3 = 1;
        $cantidad_calipega = $this->n_bajantes;
        $cantidad_plastico_negro = $this->area;

        // MANO DE OBRA //
        $cantidad_pergola = $this->area;
        $cantidad_columnas = $this->n_columnas;
        $cantidad_n_bajantes = $this->n_bajantes;
        $cantidad_aluco_bond = $cantidad_alucobond_canal;
        $cantidad_anillos = $this->anillos;


        // UNIDAD DE CADA ITEM // 
        $unidades_viga_principal_sujecion = $cantidad_viga_principal_sujecion / 6.4;
        $unidades_viga_secundaria = $cantidad_viga_secundaria / 6.4; 
        $unidades_columnas = $cantidad_columna / 6.4; 
        $unidades_anillos = $cantidad_anillo / 6.4; 
        $unidades_canal_agua = $cantidad_canal_agua / 6.4; 
        $unidades_alucobond_canal = round($cantidad_alucobond_canal / 2.9768, 0);
        $unidades_ancla = $cantidad_ancla / 6.4; 
        $unidades_andamios = intval($this->tiempoDias * $cantidad_andamios);
        $unidades_t = $cantidad_t / 6.4; 
        $unidades_angulo = $cantidad_angulo / 6.5; 
        $unidades_cinta_doble_faz = $cantidad_cinta_doble_faz / 25; 
        $unidades_silicon_sellante = $cantidad_silicon_sellante / 5; 
        $unidades_silicon_color = $cantidad_silicon_color / 6; 
        $unidades_masking = $cantidad_masking / 100; 
        $unidades_alumband = $cantidad_alumband / 5;
        $unidades_tubo_pvc_3 = $cantidad_tubo_pvc_3 / 3;

        // TEST ZONE //

        $unidades = [
            'viga_principal_sujecion' => $cantidad_viga_principal_sujecion / 6.4,
            'viga_secundaria' => $cantidad_viga_secundaria / 6.4,
            'columnas' => $cantidad_columna / 6.4,
            'anillos' => $cantidad_anillo / 6.4,
            'canal_agua' => $cantidad_canal_agua / 6.4,
            'alucobond_canal' => round($cantidad_alucobond_canal / 2.9768, 0),
            'ancla' => $cantidad_ancla / 6.4,
            'andamios' => intval($this->tiempoDias * $cantidad_andamios),
            't' => $cantidad_t / 6.4,
            'angulo' => $cantidad_angulo / 6.5,
            'cinta_doble_faz' => $cantidad_cinta_doble_faz / 25,
            'silicon_sellante' => $cantidad_silicon_sellante / 5,
            'silicon_color' => $cantidad_silicon_color / 6,
            'masking' => $cantidad_masking / 100,
            'alumband' => $cantidad_alumband / 5,
            'tubo_pvc_3' => $cantidad_tubo_pvc_3 / 3,
        ];

        dd($unidades);






        // END TEST ZONE //


        // VALORES DE PRECIOS (¡reemplazar por consulta a BD!)
        $precio_viga_principal_sujecion = 100;
        $precio_viga_secundaria = 100;
        $precio_columna = 100;
        $precio_anillo = 100;
        $precio_canal_agua = 100;
        $precio_malla = 3.15;
        $precio_alucobond_canal = 25;
        $precio_ancla = 24;
        $precio_tacos_f5 = 0.03;
        $precio_tornillos_pared = 0.06;
        $precio_tornillos_piso = 0.06;
        $precio_tornillos_aluminio = 0.08;
        $precio_fleje_metalico = 6;
        $precio_aquaprotect = 81;
        $precio_andamios = 1.25;
        $precio_t = 5.42;
        $precio_angulo = 7;
        $precio_cinta_doble_faz = 9;
        $precio_silicon_sellante = 4;
        $precio_silicon_color = 4;
        $precio_masking = 1.1;
        $precio_vidrio = 25;
        $precio_alumband = 10;
        $precio_tubo_pvc_3 = 6;
        $precio_codo_pvc_45_3 = 4;
        $precio_codo_pvc_90_3 = 4;
        $precio_calipega = 3;
        $precio_plastico_negro = 0.58;

        // MANO DE OBRA //
        $precio_pergola = 18; //LLEVA CONDICION
        $precio_columnas = 5;
        $precio_n_bajantes = 10;
        $precio_aluco_bond = 10;
        $precio_anillos = 5;











        // Cálculos según tabla de Excel
        $viga_principal_metros = ($mayor * 3) / 6.4;
        $total_viga_principal = $viga_principal_metros * $precio_viga_principal;

        $largo_viga_secundaria = ($menor + $desperdicio) * $this->numeroVigas;
        $viga_secundaria_metros = $largo_viga_secundaria / 6.4;
        $total_viga_secundaria = $viga_secundaria_metros * $precio_viga_secundaria;

        $columna_metros = ($this->columnas * $this->alto + $desperdicio) / 6.4;
        $total_columnas = $columna_metros * $precio_columna;

        $anillo_metros = ($this->anillos * (0.45 * 4)) / 6.4;
        $total_anillos = $anillo_metros * $precio_anillo;

        // PVP
        $this->pvp = $total_viga_principal + $total_viga_secundaria + $total_columnas + $total_anillos;

        // IVA y Total
        $this->iva = $this->pvp * 0.15;
        $this->total = $this->pvp + $this->iva;
    }

    public function render()
    {
        $services = Services::all();
        $variants = $this->variants;
        $clients = auth()->user()->clients;
        return view('livewire.vendor.quotes.index', compact('services', 'variants', 'clients'));
    }
}
