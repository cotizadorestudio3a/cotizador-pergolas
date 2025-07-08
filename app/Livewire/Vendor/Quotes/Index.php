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
    public ?string $selectedCuadricula = 'cuadricula_trama';
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
    public $medidaACuadricula;
    public $medidaBCuadricula;
    public $distanciaPalillajeCuadricula;
    public $altoCuadricula;

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

        // CALCULOS INICIALES DE LA PERGOLA //
        $this->numeroVigas = ceil($mayor / 0.65) + 1;
        $this->largoVigas = $menor;
        $this->largoVigaPrincipal = $mayor;
        $this->area = $this->medidaA * $this->medidaB;
        $this->tiempoDias = $this->area / 1.8;
        $this->tiempoMeses = $this->tiempoDias / 30;

        $aumento_desperdicio_vigas = ($this->largoVigas > 4 && $this->largoVigas < 6.4)
            ? -$this->largoVigas + 6.4
            : 0;
        $aumento_desperdicio_columnas =
            ($this->alto > 4 && $this->alto < 6.4)
            ? -$this->alto + 6.4
            : 0;

        //CANTIDAD DE CADA ITEM //
        $cantidad_viga_principal_sujecion = $this->largoVigaPrincipal * 3;
        $cantidad_viga_secundaria = ($aumento_desperdicio_vigas + $this->largoVigas) * $this->numeroVigas;
        $cantidad_columna = $this->n_columnas * $this->alto + $aumento_desperdicio_columnas * $this->n_columnas;
        $cantidad_anillo = $this->anillos * (0.45 * 4);
        $cantidad_canal_agua = $this->largoVigaPrincipal;
        $cantidad_malla = 0.45 * $this->largoVigaPrincipal;
        $cantidad_alucobond_canal = $this->largoVigaPrincipal * 0.3;
        $cantidad_ancla =   $this->numeroVigas * 0.6 + $this->n_columnas * 0.6;
        $cantidad_tornillos_pared = $this->numeroVigas * 6;
        $cantidad_tornillos_piso = $this->n_columnas * 6;
        $cantidad_tacos = $cantidad_tornillos_pared + $cantidad_tornillos_piso;
        $cantidad_tornillos_aluminio = $this->numeroVigas * 6 + $this->n_columnas * 12;
        $cantidad_fleje_metalico = $this->largoVigaPrincipal;
        $cantidad_aquaprotect = $cantidad_fleje_metalico * 0.1;
        $cantidad_andamios = intval(($this->alto / 2) * ($this->area / 15));
        $cantidad_t = $this->numeroVigas * $this->largoVigas;
        $cantidad_angulo = $this->largoVigaPrincipal;
        $cantidad_cinta_doble_faz = ($this->numeroVigas * $this->largoVigas) * 2;
        $cantidad_silicon_sellante = (($this->numeroVigas * $this->largoVigas) * 2) + $this->largoVigaPrincipal * intval($this->largoVigas / 2.4) + $this->largoVigaPrincipal * 4;
        $cantidad_silicon_color = $this->largoVigaPrincipal * 3 + ($this->n_columnas * 0.6) * 3;
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

        // VALORES DE PRECIOS (¡reemplazar por consulta a BD!)
        $precio_viga_principal_sujecion = 100;
        $precio_viga_secundaria = 100;
        $precio_columna = 100;
        $precio_anillo = 100;
        $precio_canal_agua = 91;
        $precio_malla = 3.15;
        $precio_alucobond_canal = 22.31;
        $precio_ancla = 24;
        $precio_tacos = 0.03;
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
        $precio_pergola = ($cantidad_pergola < 10) ? (180 / $cantidad_pergola) : 18;
        $precio_columnas = 5;
        $precio_n_bajantes = 10;
        $precio_aluco_bond = 10;
        $precio_anillos = 5;

        // END CALCULOS INICIALES DE LA PERGOLA //

        // CALCULOS PARA LA CUADRICULA // 
        $numero_cuadriculas = ceil(min($this->medidaACuadricula, $this->medidaBCuadricula) / $this->distanciaPalillajeCuadricula) + 1;
        $largo_cuadriculas = max($this->medidaACuadricula, $this->medidaBCuadricula);
        $vigas_cubierta_cuadricula = ceil(max($this->medidaACuadricula, $this->medidaBCuadricula) / 0.55) + 1;
        $largo_vigas_cuadricula = min($this->medidaBCuadricula, $this->medidaACuadricula);
        $area_cuadricula = $this->medidaBCuadricula * $this->medidaACuadricula;

        // CANTIDAD POR CADA ITEM DE LA CUADRICULA // 
        $cantidad_cuadricula = $numero_cuadriculas * $largo_cuadriculas;
        $cantidad_tornillos_cuadricula = $numero_cuadriculas * $vigas_cubierta_cuadricula;
        $cantidad_tornillos_t = ($vigas_cubierta_cuadricula * $largo_vigas_cuadricula) / 1;
        $cantidad_t_cuadricula = $vigas_cubierta_cuadricula * $largo_vigas_cuadricula;
        $cantidad_mano_de_obra_cuadricula = $area_cuadricula;

        // UNIDADES POR CADA ITEM DE LA CUADRICULA
        $unidades_cuadricula = $cantidad_cuadricula / 6.4;
        $unidades_t_cuadricula = $cantidad_t_cuadricula / 6.4;

        // PRECIOS POR CADA ITEM DE LA CUADRICULA (BD)
        $precio_cuadricula = 10;
        $precio_tornillos_cuadricula = 0.06;
        $precio_tornillos_t = 0.06;
        $precio_t_cuadricula = 5.42;
        $precio_mano_de_obra_cuadricula = 3;

        // TOTALES DE LA CUADRICULA // 
        $total_cuadricula = $precio_cuadricula * $unidades_cuadricula;
        $total_tornillos_cuadricula = $precio_tornillos_cuadricula * $cantidad_tornillos_cuadricula;
        $total_tornillos_t = $precio_tornillos_t * $cantidad_tornillos_t;
        $total_t_cuadricula = $precio_t_cuadricula * $cantidad_t_cuadricula;
        $total_mano_de_obra = $precio_mano_de_obra_cuadricula * $cantidad_mano_de_obra_cuadricula;

        $totales_cuadricula = [
            'total_cuadricula' => $total_cuadricula,
            'total_tornillos_cuadricula' => $total_tornillos_cuadricula,
            'total_tornillos_t' => $total_tornillos_t,
            'total_t_cuadricula' => $total_t_cuadricula,
            'total_mano_de_obra' => $total_mano_de_obra,
        ];


        // END CALCULOS PARA LA CUADRICULA //

        // CÁLCULO DE TOTALES
        $totales = [
            'pergola' => [
                'viga_principal_sujecion' => $unidades_viga_principal_sujecion * $precio_viga_principal_sujecion,
                'viga_secundaria' => $unidades_viga_secundaria * $precio_viga_secundaria,
                'columnas' => $unidades_columnas * $precio_columna,
                'anillos' => $unidades_anillos * $precio_anillo,
                'canal_agua' => $unidades_canal_agua * $precio_canal_agua,
                'malla' => $precio_malla * $cantidad_malla,
                'alucobond_canal' => $unidades_alucobond_canal * $precio_alucobond_canal,
                'ancla' => $unidades_ancla * $precio_ancla,
                'tacos' => $precio_tacos * $cantidad_tacos,
                'tornillos_pared' => $precio_tornillos_pared * $cantidad_tornillos_pared,
                'tornillos_piso' => $precio_tornillos_piso * $cantidad_tornillos_piso,
                'tornillos_aluminio' => $precio_tornillos_aluminio * $cantidad_tornillos_aluminio,
                'fleje_metalico' => $precio_fleje_metalico * $cantidad_fleje_metalico,
                'aquaprotect' => $precio_aquaprotect * $cantidad_aquaprotect,
                'andamios' => ($cantidad_andamios * $this->tiempoDias > $cantidad_andamios * 60)
                    ? $cantidad_andamios * 60
                    : $unidades_andamios * $precio_andamios,
                't' => $unidades_t * $precio_t,
                'angulo' => $unidades_angulo * $precio_angulo,
                'cinta_doble_faz' => $unidades_cinta_doble_faz * $precio_cinta_doble_faz,
                'silicon_sellante' => $unidades_silicon_sellante * $precio_silicon_sellante,
                'silicon_color' => $unidades_silicon_color * $precio_silicon_color,
                'masking' => $unidades_masking * $precio_masking,
                'vidrio' => $precio_vidrio * $cantidad_vidrio,
                'alumband' => $unidades_alumband * $precio_alumband,
                'tubo_pvc_3' => $unidades_tubo_pvc_3 * $precio_tubo_pvc_3,
                'codo_pvc_45_3' => $precio_codo_pvc_45_3 * $cantidad_codo_pvc_45_3,
                'codo_pvc_90_3' => $precio_codo_pvc_90_3 * $cantidad_codo_pvc_90_3,
                'calipega' => $precio_calipega * $cantidad_calipega,
                'plastico_negro' => $precio_plastico_negro * $cantidad_plastico_negro,
                'pergola' => $precio_pergola * $cantidad_pergola,
                'mano_obra_columnas' => $precio_columnas * $cantidad_columnas,
                'bajante' => $precio_n_bajantes * $cantidad_n_bajantes,
                'aluco_bond' => $precio_aluco_bond * $cantidad_aluco_bond,
                'mano_obra_anillos' => $precio_anillos * $cantidad_anillos,
            ], 
            'cuadricula' =>[
                'total_cuadricula' => $total_cuadricula,
                'total_tornillos_cuadricula' => $total_tornillos_cuadricula,
                'total_tornillos_t' => $total_tornillos_t,
                'total_t_cuadricula' => $total_t_cuadricula,
                'total_mano_de_obra' => $total_mano_de_obra,
            ]

        ];

        $alquilar_andamios =
            ($cantidad_andamios * $this->tiempoDias > $cantidad_andamios * 60)
            ? 'COMPRAR ANDAMIOS'
            : 'ALQUILAR ANDAMIOS';
        $pagar_dia_pergola = $cantidad_pergola < 10 ? "Pagar dia" : "";

        // Cálculo de costos para la pérgola
        $costo_total_pergola = round(array_sum($totales['pergola']), 2);
        $margen_negociacion_pergola = round($costo_total_pergola * 0.08, 2);
        $margen_imprevistos_pergola = round($costo_total_pergola * 0.04, 2);
        $margen_utilidad_pergola = round($costo_total_pergola * 0.44, 2);
        $costo_visualizacion_pergola = 75;
        $costo_documentacion_pergola = 50;

        // Cálculo de costos para la cuadrícula
        $costo_total_cuadricula = round(array_sum($totales['cuadricula']), 2);
        $margen_negociacion_cuadricula = round($costo_total_cuadricula * 0.08, 2);
        $margen_imprevistos_cuadricula = round($costo_total_cuadricula * 0.04, 2);
        $margen_utilidad_cuadricula = round($costo_total_cuadricula * 0.44, 2);
       
        // Cálculo del PVP de la cuadrícula (sin IVA)
        $pvp_cuadricula = round($costo_total_cuadricula + 
                               $margen_negociacion_cuadricula + 
                               $margen_imprevistos_cuadricula + 
                               $margen_utilidad_cuadricula);

        // Cálculo del PVP de la pérgola (sin IVA)
        $pvp_pergola = $costo_total_pergola + 
                       $margen_negociacion_pergola + 
                       $margen_imprevistos_pergola + 
                       $margen_utilidad_pergola + 
                       $costo_visualizacion_pergola + 
                       $costo_documentacion_pergola;

        // Cálculo del PVP total (pérgola + cuadrícula, sin IVA)
        $pvp_total = $pvp_pergola + $pvp_cuadricula;

        // Cálculo del IVA (15% del PVP total)
        $iva_total = $pvp_total * 0.15;

        // Cálculo del total (PVP total + IVA)
        $total = $pvp_total + $iva_total;

        // Asignación a las propiedades
        $this->pvp = round($pvp_total);
        $this->iva = $iva_total;
        $this->total = $total;
    }

    public function render()
    {
        $services = Services::all();
        $variants = $this->variants;
        $clients = auth()->user()->clients;
        return view('livewire.vendor.quotes.index', compact('services', 'variants', 'clients'));
    }
}
