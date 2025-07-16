<?php

namespace App\Services\Pergolas;

use Barryvdh\DomPDF\Facade\Pdf;

class PergolaVidrio extends PergolaBase
{

    // INPUTS INICIALES //
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

    // Flags de desperdicio
    public $aumento_desperdicio_vigas;
    public $aumento_desperdicio_columnas;

    // Decisiones
    public $alquilar_andamios;
    public $pagar_dia_pergola;

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
    public $unidades_columna;
    public $unidades_anillo;
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

    // Totales generales
    public $total_pergola;
    public $margen_negociacion;
    public $margen_imprevistos;
    public $margen_utilidad;
    public $costo_visualizacion;
    public $costo_documentacion;
    public $pvp_pergola;
    public $iva_total;
    public $total;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->medidaA = $data['medidaA'];
        $this->medidaB = $data['medidaB'];
        $this->alto = $data['alto'];
        $this->n_columnas = $data['n_columnas'];
        $this->n_bajantes = $data['n_bajantes'];
        $this->anillos = $data['anillos'];
    }

    public function calcular(): array
    {
        $this->calcularDimensiones();
        $this->calcularCantidades();
        $this->calcularUnidades();
        $this->definirPrecios();
        $this->calcularTotales();

        return [
            'pvp_pergola' => $this->pvp_pergola,
            'iva_pergola' => $this->iva_total,
            'total_pergola' => $this->total,
        ];
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
        $this->unidades_columna = $this->cantidad_columna / 6.4;
        $this->unidades_anillo = $this->cantidad_anillo / 6.4;
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

    private function calcularTotales()
    {
        $this->total_pergola = (
            $this->unidades_viga_principal_sujecion * $this->precio_viga_principal_sujecion +
            $this->unidades_viga_secundaria * $this->precio_viga_secundaria +
            $this->unidades_columna * $this->precio_columna +
            $this->unidades_anillo * $this->precio_anillo +
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

        $this->margen_negociacion = $this->total_pergola * 0.08;
        $this->margen_imprevistos = $this->total_pergola * 0.04;
        $this->margen_utilidad = $this->total_pergola * 0.44;
        $this->costo_visualizacion = 75;
        $this->costo_documentacion = 50;

        $this->alquilar_andamios =
            ($this->cantidad_andamios * $this->tiempoDias > $this->cantidad_andamios * 60)
            ? 'COMPRAR ANDAMIOS'
            : 'ALQUILAR ANDAMIOS';
        $this->pagar_dia_pergola = $this->cantidad_pergola < 10 ? "PAGAR DIA" : "";


        $this->pvp_pergola =
            $this->total_pergola +
            $this->margen_negociacion +
            $this->margen_imprevistos +
            $this->margen_utilidad +
            $this->costo_visualizacion +
            $this->costo_documentacion;

        $this->total = $this->pvp_pergola;
    }

    public function obtenerDetalleMateriales(): array
    {
        $materiales = [
            'Viga Principal Sujeción' => 'viga_principal_sujecion',
            'Viga Secundaria' => 'viga_secundaria',
            'Columna (ml)' => 'columna',
            'Anillo (ml)' => 'anillo',
            'Canal de Agua' => 'canal_agua',
            'Malla' => 'malla',
            'Alucobond Canal' => 'alucobond_canal',
            'Ancla' => 'ancla',
            'Tacos' => 'tacos',
            'Tornillos Pared' => 'tornillos_pared',
            'Tornillos Piso' => 'tornillos_piso',
            'Tornillos Aluminio' => 'tornillos_aluminio',
            'Fleje Metalico' => 'fleje_metalico',
            'Aquaprotect' => 'aquaprotect',
            'Andamios' => 'andamios',
            'T' => 't',
            'Ángulo' => 'angulo',
            'Cinta Doble Faz' => 'cinta_doble_faz',
            'Silicón Sellante' => 'silicon_sellante',
            'Silicón Color' => 'silicon_color',
            'Masking' => 'masking',
            'Alumband' => 'alumband',
            'Vidrio' => 'vidrio',
            'Tubo PVC 3”' => 'tubo_pvc_3',
            'Codo PVC 45° 3”' => 'codo_pvc_45_3',
            'Codo PVC 90° 3”' => 'codo_pvc_90_3',
            'Calipega' => 'calipega',
            'Plástico Negro' => 'plastico_negro',
            'Pergola (m²)' => 'pergola',
            'Columnas (uds)' => 'columnas',
            'N Bajantes' => 'n_bajantes',
            'Aluco Bond' => 'aluco_bond',
            'Anillos (uds)' => 'anillos',
        ];

        // Materiales que deben calcular el total con 'cantidad * precio'
        $usarCantidadParaTotal = [
            'malla',
            'tacos',
            'tornillos_pared',
            'tornillos_piso',
            'tornillos_aluminio',
            'fleje_metalico',
            'aquaprotect',
            'vidrio',
            'codo_pvc_45_3',
            'codo_pvc_90_3',
            'calipega',
            'plastico_negro',
            'pergola',
            'columnas',
            'n_bajantes',
            'aluco_bond',
            'anillos',
        ];


        $detalle = [];
        foreach ($materiales as $nombre => $clave) {
            $cantidad = $this->{'cantidad_' . $clave} ?? 0;
            $unidades = $this->{'unidades_' . $clave} ?? 0;
            $precio = $this->{'precio_' . $clave} ?? 0;

            $total = in_array($clave, $usarCantidadParaTotal)
            ? $cantidad * $precio
            : $unidades * $precio;

            $detalle[$nombre] = [
                'cantidad' => $cantidad,
                'unidades' => $unidades,
                'precio_unitario' => $precio,
                'total' => $total
            ];
        }
        return $detalle;
    }




    public function obtenerPDFCotizacion(): string
    {
        // Aquí deberías generar un PDF con el resumen comercial
        // usando DomPDF, Barryvdh, Snappy, etc.
        return 'ruta/a/pdf_cotizacion.pdf';
    }

    public function obtenerPDFOrdenProduccion(): string
    {
        $materiales = $this->obtenerDetalleMateriales();
        $data = [
            'materiales' => $materiales,
            'medidas' => [
                'medidaA' => $this->medidaA,
                'medidaB' => $this->medidaB,
                'alto' => $this->alto,
                'area' => $this->area,
            ],
            'extras' => [
                'estrategia_andamios' => $this->alquilar_andamios,
                'nota_pago_por_dia' => $this->pagar_dia_pergola
            ], 
            'titulo' => [
                'titulo_servicio' => 'Pergola de Vidrio'
            ]
        ];

        $pdf = Pdf::loadView('pdfs.orden-produccion', $data);
        $path = 'pdf/orden_produccion_' . now()->timestamp . '.pdf';
        $pdf->save(storage_path('app/public/' . $path));
        
        return $path;
    }
}
