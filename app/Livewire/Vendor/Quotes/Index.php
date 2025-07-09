<?php

namespace App\Livewire\Vendor\Quotes;

use App\Models\Services;
use App\Models\ServiceVariants;
use Livewire\Component;

class Index extends Component
{
    public int $step = 1;          // 1 = servicio-color, 2 = variante
    public ?int $selectedService = null;
    public array $selectedColor = [];        // [service_id => color]
    public ?int $selectedVariant = null;      // id de la variante
    public ?string $selectedCuadricula;
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
    public $medidaACuadriculaTrama;
    public $medidaBCuadriculaTrama;
    public $distanciaPalillajeCuadriculaTrama;
    public $altoCuadriculaTrama;

    /* --- Propiedades calculadas dinámicamente (nuevas) --- */
    // Flags de desperdicio
    public $aumento_desperdicio_vigas;
    public $aumento_desperdicio_columnas;

    // Cantidades
    public $cantidad_viga_principal_sujecion;
    public $cantidad_viga_secundaria;
    public $cantidad_columna;
    public $cantidad_anillo;
    public $cantidad_canal_agua;
    public $cantidad_malla;
    public $cantidad_alucobond_canal;
    public $cantidad_ancla;
    public $cantidad_tornillos_pared;
    public $cantidad_tornillos_piso;
    public $cantidad_tacos;
    public $cantidad_tornillos_aluminio;
    public $cantidad_fleje_metalico;
    public $cantidad_aquaprotect;
    public $cantidad_andamios;
    public $cantidad_t;
    public $cantidad_angulo;
    public $cantidad_cinta_doble_faz;
    public $cantidad_silicon_sellante;
    public $cantidad_silicon_color;
    public $cantidad_masking;
    public $cantidad_vidrio;
    public $cantidad_alumband;
    public $cantidad_tubo_pvc_3;
    public $cantidad_codo_pvc_45_3;
    public $cantidad_codo_pvc_90_3;
    public $cantidad_calipega;
    public $cantidad_plastico_negro;
    public $cantidad_pergola;
    public $cantidad_columnas;
    public $cantidad_n_bajantes;
    public $cantidad_aluco_bond;
    public $cantidad_anillos;

    // Unidades
    public $unidades_viga_principal_sujecion;
    public $unidades_viga_secundaria;
    public $unidades_columnas;
    public $unidades_anillos;
    public $unidades_canal_agua;
    public $unidades_alucobond_canal;
    public $unidades_ancla;
    public $unidades_andamios;
    public $unidades_t;
    public $unidades_angulo;
    public $unidades_cinta_doble_faz;
    public $unidades_silicon_sellante;
    public $unidades_silicon_color;
    public $unidades_masking;
    public $unidades_alumband;
    public $unidades_tubo_pvc_3;

    // Precios
    public $precio_viga_principal_sujecion;
    public $precio_viga_secundaria;
    public $precio_columna;
    public $precio_anillo;
    public $precio_canal_agua;
    public $precio_malla;
    public $precio_alucobond_canal;
    public $precio_ancla;
    public $precio_tacos;
    public $precio_tornillos_pared;
    public $precio_tornillos_piso;
    public $precio_tornillos_aluminio;
    public $precio_fleje_metalico;
    public $precio_aquaprotect;
    public $precio_andamios;
    public $precio_t;
    public $precio_angulo;
    public $precio_cinta_doble_faz;
    public $precio_silicon_sellante;
    public $precio_silicon_color;
    public $precio_masking;
    public $precio_vidrio;
    public $precio_alumband;
    public $precio_tubo_pvc_3;
    public $precio_codo_pvc_45_3;
    public $precio_codo_pvc_90_3;
    public $precio_calipega;
    public $precio_plastico_negro;
    public $precio_pergola;
    public $precio_columnas;
    public $precio_n_bajantes;
    public $precio_aluco_bond;
    public $precio_anillos;

    // Datos cuadricula(s)
    public $numero_cuadriculas;
    public $largo_cuadriculas;
    public $vigas_cubierta_cuadricula;
    public $largo_vigas_cuadricula;
    public $area_cuadricula;
    public $total_cuadricula;
    public $total_tornillos_cuadricula;
    public $total_tornillos_t;
    public $total_t_cuadricula;
    public $total_mano_de_obra;

    // Cantidades
    public $cantidad_cuadricula;
    public $cantidad_tornillos_cuadricula;
    public $cantidad_tornillos_t;
    public $cantidad_t_cuadricula;
    public $cantidad_mano_de_obra_cuadricula;

    // Unidades
    public $unidades_cuadricula;
    public $unidades_t_cuadricula;

    // Precios
    public $precio_cuadricula;
    public $precio_tornillos_cuadricula;
    public $precio_tornillos_t;
    public $precio_t_cuadricula;
    public $precio_mano_de_obra_cuadricula;


    // Totales generales
    public $total_pergola;
    public $margen_negociacion;
    public $margen_imprevistos;
    public $margen_utilidad;
    public $costo_visualizacion;
    public $costo_documentacion;

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
        $this->calcularDimensiones();
        $this->calcularCantidades();
        $this->calcularUnidades();
        $this->definirPrecios();

        if ($this->selectedCuadricula === 'cuadricula') {
            $this->calcularCuadricula();
        } else {
            $this->calcularCuadriculaTrama();
        }

        $this->calcularTotales();
    }

    private function calcularDimensiones()
    {
        $mayor = max($this->medidaA, $this->medidaB);
        $menor = min($this->medidaA, $this->medidaB);

        $this->numeroVigas = ceil($mayor / 0.65) + 1;
        $this->largoVigas = $menor;
        $this->largoVigaPrincipal = $mayor;
        $this->area = $this->medidaA * $this->medidaB;
        $this->tiempoDias = $this->area / 1.8;
        $this->tiempoMeses = $this->tiempoDias / 30;

        $this->aumento_desperdicio_vigas = ($this->largoVigas > 4 && $this->largoVigas < 6.4)
            ? -$this->largoVigas + 6.4 : 0;

        $this->aumento_desperdicio_columnas = ($this->alto > 4 && $this->alto < 6.4)
            ? -$this->alto + 6.4 : 0;
    }

    private function calcularCantidades()
    {
        $this->cantidad_viga_principal_sujecion = $this->largoVigaPrincipal * 3;
        $this->cantidad_viga_secundaria = ($this->aumento_desperdicio_vigas + $this->largoVigas) * $this->numeroVigas;
        $this->cantidad_columna = $this->n_columnas * $this->alto + $this->aumento_desperdicio_columnas * $this->n_columnas;
        $this->cantidad_anillo = $this->anillos * (0.45 * 4);
        $this->cantidad_canal_agua = $this->largoVigaPrincipal;
        $this->cantidad_malla = 0.45 * $this->largoVigaPrincipal;
        $this->cantidad_alucobond_canal = $this->largoVigaPrincipal * 0.3;
        $this->cantidad_ancla = $this->numeroVigas * 0.6 + $this->n_columnas * 0.6;
        $this->cantidad_tornillos_pared = $this->numeroVigas * 6;
        $this->cantidad_tornillos_piso = $this->n_columnas * 6;
        $this->cantidad_tacos = $this->cantidad_tornillos_pared + $this->cantidad_tornillos_piso;
        $this->cantidad_tornillos_aluminio = $this->numeroVigas * 6 + $this->n_columnas * 12;
        $this->cantidad_fleje_metalico = $this->largoVigaPrincipal;
        $this->cantidad_aquaprotect = $this->cantidad_fleje_metalico * 0.1;
        $this->cantidad_andamios = intval(($this->alto / 2) * ($this->area / 15));
        $this->cantidad_t = $this->numeroVigas * $this->largoVigas;
        $this->cantidad_angulo = $this->largoVigaPrincipal;
        $this->cantidad_cinta_doble_faz = ($this->numeroVigas * $this->largoVigas) * 2;
        $this->cantidad_silicon_sellante = (($this->numeroVigas * $this->largoVigas) * 2) + $this->largoVigaPrincipal * intval($this->largoVigas / 2.4) + $this->largoVigaPrincipal * 4;
        $this->cantidad_silicon_color = $this->largoVigaPrincipal * 3 + ($this->n_columnas * 0.6) * 3;
        $this->cantidad_masking = ($this->cantidad_silicon_color + $this->cantidad_silicon_sellante) * 2;
        $this->cantidad_vidrio = $this->area;
        $this->cantidad_alumband = $this->largoVigaPrincipal * 2;
        $this->cantidad_tubo_pvc_3 = $this->n_bajantes * $this->alto * 1.2;
        $this->cantidad_codo_pvc_45_3 = 2;
        $this->cantidad_codo_pvc_90_3 = 1;
        $this->cantidad_calipega = $this->n_bajantes;
        $this->cantidad_plastico_negro = $this->area;

        $this->cantidad_pergola = $this->area;
        $this->cantidad_columnas = $this->n_columnas;
        $this->cantidad_n_bajantes = $this->n_bajantes;
        $this->cantidad_aluco_bond = $this->cantidad_alucobond_canal;
        $this->cantidad_anillos = $this->anillos;
    }

    private function calcularUnidades()
    {
        $this->unidades_viga_principal_sujecion = $this->cantidad_viga_principal_sujecion / 6.4;
        $this->unidades_viga_secundaria = $this->cantidad_viga_secundaria / 6.4;
        $this->unidades_columnas = $this->cantidad_columna / 6.4;
        $this->unidades_anillos = $this->cantidad_anillo / 6.4;
        $this->unidades_canal_agua = $this->cantidad_canal_agua / 6.4;
        $this->unidades_alucobond_canal = round($this->cantidad_alucobond_canal / 2.9768, 0);
        $this->unidades_ancla = $this->cantidad_ancla / 6.4;
        $this->unidades_andamios = intval($this->tiempoDias * $this->cantidad_andamios);
        $this->unidades_t = $this->cantidad_t / 6.4;
        $this->unidades_angulo = $this->cantidad_angulo / 6.5;
        $this->unidades_cinta_doble_faz = $this->cantidad_cinta_doble_faz / 25;
        $this->unidades_silicon_sellante = $this->cantidad_silicon_sellante / 5;
        $this->unidades_silicon_color = $this->cantidad_silicon_color / 6;
        $this->unidades_masking = $this->cantidad_masking / 100;
        $this->unidades_alumband = $this->cantidad_alumband / 5;
        $this->unidades_tubo_pvc_3 = $this->cantidad_tubo_pvc_3 / 3;
    }

    private function definirPrecios()
    {
        $this->precio_viga_principal_sujecion = 100;
        $this->precio_viga_secundaria = 100;
        $this->precio_columna = 100;
        $this->precio_anillo = 100;
        $this->precio_canal_agua = 91;
        $this->precio_malla = 3.15;
        $this->precio_alucobond_canal = 22.31;
        $this->precio_ancla = 24;
        $this->precio_tacos = 0.03;
        $this->precio_tornillos_pared = 0.06;
        $this->precio_tornillos_piso = 0.06;
        $this->precio_tornillos_aluminio = 0.08;
        $this->precio_fleje_metalico = 6;
        $this->precio_aquaprotect = 81;
        $this->precio_andamios = 1.25;
        $this->precio_t = 5.42;
        $this->precio_angulo = 7;
        $this->precio_cinta_doble_faz = 9;
        $this->precio_silicon_sellante = 4;
        $this->precio_silicon_color = 4;
        $this->precio_masking = 1.1;
        $this->precio_vidrio = 25;
        $this->precio_alumband = 10;
        $this->precio_tubo_pvc_3 = 6;
        $this->precio_codo_pvc_45_3 = 4;
        $this->precio_codo_pvc_90_3 = 4;
        $this->precio_calipega = 3;
        $this->precio_plastico_negro = 0.58;
        $this->precio_pergola = ($this->cantidad_pergola < 10) ? (180 / $this->cantidad_pergola) : 18;
        $this->precio_columnas = 5;
        $this->precio_n_bajantes = 10;
        $this->precio_aluco_bond = 10;
        $this->precio_anillos = 5;
    }

    private function calcularCuadricula()
    {
        $this->numero_cuadriculas = ceil(min($this->medidaACuadricula, $this->medidaBCuadricula) / $this->distanciaPalillajeCuadricula) + 1;
        $this->largo_cuadriculas = max($this->medidaACuadricula, $this->medidaBCuadricula);
        $this->vigas_cubierta_cuadricula = ceil(max($this->medidaACuadricula, $this->medidaBCuadricula) / 0.55) + 1;
        $this->largo_vigas_cuadricula = min($this->medidaBCuadricula, $this->medidaACuadricula);
        $this->area_cuadricula = $this->medidaBCuadricula * $this->medidaACuadricula;

        $this->total_cuadricula = 10 * ($this->numero_cuadriculas * $this->largo_cuadriculas) / 6.4;
        $this->total_tornillos_cuadricula = 0.06 * $this->numero_cuadriculas * $this->vigas_cubierta_cuadricula;
        $this->total_tornillos_t = 0.06 * ($this->vigas_cubierta_cuadricula * $this->largo_vigas_cuadricula);
        $this->total_t_cuadricula = 5.42 * $this->vigas_cubierta_cuadricula * $this->largo_vigas_cuadricula;
        $this->total_mano_de_obra = 3 * $this->area_cuadricula;
    }

    private function calcularCantidadesCuadricula()
    {
        $this->cantidad_cuadricula = $this->numero_cuadriculas * $this->largo_cuadriculas;
        $this->cantidad_tornillos_cuadricula = $this->numero_cuadriculas * $this->vigas_cubierta_cuadricula;
        $this->cantidad_tornillos_t = ($this->vigas_cubierta_cuadricula * $this->largo_vigas_cuadricula) / 1;
        $this->cantidad_t_cuadricula = $this->vigas_cubierta_cuadricula * $this->largo_vigas_cuadricula;
        $this->cantidad_mano_de_obra_cuadricula = $this->area_cuadricula;
    }

    private function calcularUnidadesCuadricula()
    {
        $this->unidades_cuadricula = $this->cantidad_cuadricula / 6.4;
        $this->unidades_t_cuadricula = $this->cantidad_t_cuadricula / 6.4;
    }

    private function definirPreciosCuadricula()
    {
        $this->precio_cuadricula = 10;
        $this->precio_tornillos_cuadricula = 0.06;
        $this->precio_tornillos_t = 0.06;
        $this->precio_t_cuadricula = 5.42;
        $this->precio_mano_de_obra_cuadricula = 3;
    }

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

    private function calcularTotales()
    {
        $this->total_pergola = (
            $this->unidades_viga_principal_sujecion * $this->precio_viga_principal_sujecion +
            $this->unidades_viga_secundaria * $this->precio_viga_secundaria +
            $this->unidades_columnas * $this->precio_columna +
            $this->unidades_anillos * $this->precio_anillo +
            $this->unidades_canal_agua * $this->precio_canal_agua +
            $this->precio_malla * $this->cantidad_malla +
            $this->unidades_alucobond_canal * $this->precio_alucobond_canal +
            $this->unidades_ancla * $this->precio_ancla +
            $this->precio_tacos * $this->cantidad_tacos +
            $this->precio_tornillos_pared * $this->cantidad_tornillos_pared +
            $this->precio_tornillos_piso * $this->cantidad_tornillos_piso +
            $this->precio_tornillos_aluminio * $this->cantidad_tornillos_aluminio +
            $this->precio_fleje_metalico * $this->cantidad_fleje_metalico +
            $this->precio_aquaprotect * $this->cantidad_aquaprotect +
            $this->unidades_andamios * $this->precio_andamios +
            $this->unidades_t * $this->precio_t +
            $this->unidades_angulo * $this->precio_angulo +
            $this->unidades_cinta_doble_faz * $this->precio_cinta_doble_faz +
            $this->unidades_silicon_sellante * $this->precio_silicon_sellante +
            $this->unidades_silicon_color * $this->precio_silicon_color +
            $this->unidades_masking * $this->precio_masking +
            $this->precio_vidrio * $this->cantidad_vidrio +
            $this->unidades_alumband * $this->precio_alumband +
            $this->unidades_tubo_pvc_3 * $this->precio_tubo_pvc_3 +
            $this->precio_codo_pvc_45_3 * $this->cantidad_codo_pvc_45_3 +
            $this->precio_codo_pvc_90_3 * $this->cantidad_codo_pvc_90_3 +
            $this->precio_calipega * $this->cantidad_calipega +
            $this->precio_plastico_negro * $this->cantidad_plastico_negro +
            $this->precio_pergola * $this->cantidad_pergola +
            $this->precio_columnas * $this->cantidad_columnas +
            $this->precio_n_bajantes * $this->cantidad_n_bajantes +
            $this->precio_aluco_bond * $this->cantidad_aluco_bond +
            $this->precio_anillos * $this->cantidad_anillos
        );

        $this->total_cuadricula = [
            'total_cuadricula' => $this->total_cuadricula,
            'total_tornillos_cuadricula' => $this->total_tornillos_cuadricula,
            'total_tornillos_t' => $this->total_tornillos_t,
            'total_t_cuadricula' => $this->total_t_cuadricula,
            'total_mano_de_obra' => $this->total_mano_de_obra,
        ];

        $this->total_cuadricula = array_sum($this->total_cuadricula);

        $margen_negociacion_cuadricula = $this->total_cuadricula * 0.08;
        $margen_imprevistos_cuadricula = $this->total_cuadricula * 0.04;
        $margen_utilidad_cuadricula = $this->total_cuadricula * 0.44;

        $pvp_cuadricula = $this->total_cuadricula + $margen_negociacion_cuadricula + $margen_imprevistos_cuadricula + $margen_utilidad_cuadricula;
        $iva_cuadricula = $pvp_cuadricula * 0.15;
        $total_cuadricula = $pvp_cuadricula + $iva_cuadricula; 

        $this->margen_negociacion = $this->total_pergola * 0.08;
        $this->margen_imprevistos = $this->total_pergola * 0.04;
        $this->margen_utilidad = $this->total_pergola * 0.44;
        $this->costo_visualizacion = 75;
        $this->costo_documentacion = 50;

        $pvp_pergola =
        $this->total_pergola +
            $this->margen_negociacion +
            $this->margen_imprevistos +
            $this->margen_utilidad +
            $this->costo_visualizacion +
            $this->costo_documentacion;

        $pvp_total = $pvp_cuadricula + $pvp_pergola;
        $iva_total = $pvp_total * 0.15;
        $total = $pvp_total + $iva_total;

        $this->pvp = $pvp_total;
        $this->iva = $iva_total;
        $this->total = $total;
    }


    /* LEGACY SAFE ZONE WORKING CODE

    public function calcularTotal()
    {
        $this->validate([
            'medidaA' => 'required|numeric',
            'medidaB' => 'required|numeric',
            'alto' => 'required|numeric',
            'n_columnas' => 'required|numeric',
            'n_bajantes' => 'required|numeric',
            'anillos' => 'required|numeric',
        ]);



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


        if ($this->selectedCuadricula === 'cuadricula') {
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

            // END CALCULOS PARA LA CUADRICULA //
        } else {

            // CALCULOS PARA LA CUADRICULA TRAMA //
            $numero_cuadriculas_trama = ceil(min($this->medidaACuadriculaTrama, $this->medidaBCuadriculaTrama) / $this->distanciaPalillajeCuadriculaTrama) + 1;
            $largo_cuadriculas_trama = max($this->medidaACuadriculaTrama, $this->medidaBCuadriculaTrama);
            $vigas_cubierta_cuadricula_trama = ceil(max($this->medidaACuadriculaTrama, $this->medidaBCuadriculaTrama) / 0.55) + 1;
            $largo_vigas_cuadricula_trama = ceil(min($this->medidaBCuadriculaTrama, $this->distanciaPalillajeCuadriculaTrama) / 0.55) + 1;;
            $area_cuadricula_trama = $this->medidaBCuadriculaTrama * $this->medidaACuadriculaTrama;

            // CANTIDAD POR CADA ITEM DE LA CUADRICULA TRAMA//
            $cantidad_cuadricula_trama = $numero_cuadriculas_trama * $largo_cuadriculas_trama;
            $cantidad_tornillos_cuadricula_trama = $numero_cuadriculas_trama * $vigas_cubierta_cuadricula_trama;
            $cantidad_tornillos_t_trama = ($vigas_cubierta_cuadricula_trama * $largo_vigas_cuadricula_trama) / 1;
            $cantidad_t_cuadricula_trama = ($vigas_cubierta_cuadricula_trama * $largo_vigas_cuadricula_trama) * 2;
            $cantidad_mano_de_obra_cuadricula_trama = $area_cuadricula_trama;

            // UNIDADES POR CADA ITEM DE LA CUADRICULA TRAMA
            $unidades_cuadricula_trama = $cantidad_cuadricula_trama / 6.4;
            $unidades_t_cuadricula_trama = $cantidad_t_cuadricula_trama / 6.4;

            // PRECIOS POR CADA ITEM DE LA CUADRICULA TRAMA (BD)
            $precio_cuadricula_trama = 10;
            $precio_tornillos_cuadricula_trama = 0.06;
            $precio_tornillos_t_trama = 0.06;
            $precio_t_cuadricula_trama = 5.42;
            $precio_mano_de_obra_cuadricula_trama = 5;

            // TOTALES DE LA CUADRICULA TRAMA //
            $total_cuadricula_trama = $precio_cuadricula_trama * $unidades_cuadricula_trama;
            $total_tornillos_cuadricula_trama = $precio_tornillos_cuadricula_trama * $cantidad_tornillos_cuadricula_trama;
            $total_tornillos_t_trama = $precio_tornillos_t_trama * $cantidad_tornillos_t_trama;
            $total_t_cuadricula_trama = $precio_t_cuadricula_trama * $cantidad_t_cuadricula_trama;
            $total_mano_de_obra_trama = $precio_mano_de_obra_cuadricula_trama * $cantidad_mano_de_obra_cuadricula_trama;

            $totales_cuadricula_trama = [
                'total_cuadricula_trama' => $total_cuadricula_trama,
                'total_tornillos_cuadricula_trama' => $total_tornillos_cuadricula_trama,
                'total_tornillos_t_trama' => $total_tornillos_t_trama,
                'total_t_cuadricula_trama' => $total_t_cuadricula_trama,
                'total_mano_de_obra_trama' => $total_mano_de_obra_trama,
            ];

            $total_trama = array_sum($totales_cuadricula_trama);
            $costo_total_cuadricula_trama = round($total_trama, 2);
            $margen_negociacion_cuadricula_trama = round($costo_total_cuadricula_trama * 0.08, 2);
            $margen_imprevistos_cuadricula_trama = round($costo_total_cuadricula_trama * 0.04, 2);
            $margen_utilidad_cuadricula_trama = round($costo_total_cuadricula_trama * 0.44, 2);

            // Cálculo del PVP de la cuadrícula (sin IVA)
            $pvp_cuadricula_trama = round($costo_total_cuadricula_trama +
                $margen_negociacion_cuadricula_trama +
                $margen_imprevistos_cuadricula_trama +
                $margen_utilidad_cuadricula_trama);

            // END CALCULOS PARA LA CUADRICULA TRAMA//
        }

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
            'cuadricula' => [
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

    */

    public function render()
    {
        $services = Services::all();
        $variants = $this->variants;
        $clients = auth()->user()->clients;
        return view('livewire.vendor.quotes.index', compact('services', 'variants', 'clients'));
    }
}
