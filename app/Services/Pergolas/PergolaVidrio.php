<?php

namespace App\Services\Pergolas;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Material;
use Illuminate\Support\Facades\File;

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

    public $quotation_id;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->medidaA = max(0.1, (float)($data['medidaA'] ?? 0.1));
        $this->medidaB = max(0.1, (float)($data['medidaB'] ?? 0.1));
        $this->alto = max(0.1, (float)($data['alto'] ?? 0.1));
        $this->n_columnas = max(1, (int)($data['n_columnas'] ?? 1));
        $this->n_bajantes = max(1, (int)($data['n_bajantes'] ?? 1));
        $this->anillos = max(0, (int)($data['anillos'] ?? 0));
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
        $this->tiempoDias = $this->area > 0 ? $this->area / 1.8 : 0;
        $this->tiempoMeses = $this->tiempoDias > 0 ? $this->tiempoDias / 30 : 0;

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
        // Obtener todos los precios desde la base de datos
        $materialPrices = Material::getAllPricesArray();
        
        $this->precio_viga_principal_sujecion = $materialPrices['viga_principal_sujecion'] ?? 100;
        $this->precio_viga_secundaria = $materialPrices['viga_secundaria'] ?? 100;
        $this->precio_columna = $materialPrices['columna'] ?? 100;
        $this->precio_anillo = $materialPrices['anillo'] ?? 100;
        $this->precio_canal_agua = $materialPrices['canal_agua'] ?? 91;
        $this->precio_malla = $materialPrices['malla'] ?? 3.15;
        $this->precio_alucobond_canal = $materialPrices['alucobond_canal'] ?? 22.31;
        $this->precio_ancla = $materialPrices['ancla'] ?? 24;
        $this->precio_tacos = $materialPrices['tacos'] ?? 0.03;
        $this->precio_tornillos_pared = $materialPrices['tornillos_pared'] ?? 0.06;
        $this->precio_tornillos_piso = $materialPrices['tornillos_piso'] ?? 0.06;
        $this->precio_tornillos_aluminio = $materialPrices['tornillos_aluminio'] ?? 0.08;
        $this->precio_fleje_metalico = $materialPrices['fleje_metalico'] ?? 6;
        $this->precio_aquaprotect = $materialPrices['aquaprotect'] ?? 81;
        $this->precio_andamios = $materialPrices['andamios'] ?? 1.25;
        $this->precio_t = $materialPrices['t'] ?? 5.42;
        $this->precio_angulo = $materialPrices['angulo'] ?? 7;
        $this->precio_cinta_doble_faz = $materialPrices['cinta_doble_faz'] ?? 9;
        $this->precio_silicon_sellante = $materialPrices['silicon_sellante'] ?? 4;
        $this->precio_silicon_color = $materialPrices['silicon_color'] ?? 4;
        $this->precio_masking = $materialPrices['masking'] ?? 1.1;
        $this->precio_vidrio = $materialPrices['vidrio'] ?? 25;
        $this->precio_alumband = $materialPrices['alumband'] ?? 10;
        $this->precio_tubo_pvc_3 = $materialPrices['tubo_pvc_3'] ?? 6;
        $this->precio_codo_pvc_45_3 = $materialPrices['codo_pvc_45_3'] ?? 4;
        $this->precio_codo_pvc_90_3 = $materialPrices['codo_pvc_90_3'] ?? 4;
        $this->precio_calipega = $materialPrices['calipega'] ?? 3;
        $this->precio_plastico_negro = $materialPrices['plastico_negro'] ?? 0.58;
        $this->precio_pergola = ($this->cantidad_pergola > 0 && $this->cantidad_pergola < 10) ? (180 / $this->cantidad_pergola) : 18;
        $this->precio_columnas = $materialPrices['columnas'] ?? 5;
        $this->precio_n_bajantes = $materialPrices['n_bajantes'] ?? 10;
        $this->precio_aluco_bond = $materialPrices['aluco_bond'] ?? 10;
        $this->precio_anillos = $materialPrices['anillos'] ?? 5;
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

        // Obtener todos los materiales con sus códigos desde la base de datos
        $materialesConCodigos = Material::pluck('code', 'name')->toArray();

        $detalle = [];
        foreach ($materiales as $nombre => $clave) {
            $cantidad = $this->{'cantidad_' . $clave} ?? 0;
            $unidades = $this->{'unidades_' . $clave} ?? 0;
            $precio = $this->{'precio_' . $clave} ?? 0;

            $total = in_array($clave, $usarCantidadParaTotal)
            ? $cantidad * $precio
            : $unidades * $precio;

            // Buscar el código del material en la base de datos
            $codigoMaterial = $materialesConCodigos[$clave] ?? 'N/A';

            $detalle[$nombre] = [
                'codigo' => $codigoMaterial,
                'nombre' => $nombre,
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
        $this->quotation_id = $this->data['quotation_id'] ?? 'COT-' . now()->timestamp;

        // Si tenemos información de todos los servicios (PDF consolidado)
        if (isset($this->data['all_services']) && !empty($this->data['all_services'])) {
            $servicios = $this->processAllServicesForPDF($this->data['all_services']);
        } else {
            // Fallback: Procesar solo el servicio actual (compatibilidad)
            $servicios = $this->processSingleServiceForPDF();
        }

        $data = [
            'numero_cotizacion' => $this->data['quotation_id'] ?? 'COT-' . now()->timestamp,
            'cliente' => [
                'nombre' => $this->data['client_name'] ?? 'Cliente',
                'dni' => $this->data['client_dni'] ?? '',
                'telefono' => $this->data['client_phone'] ?? '',
                'provincia' => $this->data['client_province'] ?? ''
            ],
            'servicios' => $servicios,
            'resumen_financiero' => [
                'pvp' => $this->data['pvp'] ?? $this->pvp_pergola,
                'iva' => $this->data['iva'] ?? ($this->pvp_pergola * 0.15),
                'total' => $this->data['total'] ?? ($this->pvp_pergola * 1.15)
            ],
            'fecha_emision' => now()->format('d/m/Y'),
            'vigencia' => now()->addDays(30)->format('d/m/Y')
        ];

        $pdf = Pdf::loadView('pdfs.cotizacion', $data);
        $path = 'pdf/cotizacion_' . now()->timestamp . '.pdf';
        $pdf->save(storage_path('app/public/' . $path));
        
        return $path;
    }

    /**
     * Procesa todos los servicios para el PDF consolidado
     */
    private function processAllServicesForPDF(array $allServices): array
    {
        $servicios = [];
        $servicesDetail = $this->data['services_detail'] ?? [];

        foreach ($allServices as $index => $service) {
            $inputs = $service['inputs'];
            
            // Obtener nombre de la variante
            $variantName = 'Vidrio'; // Valor por defecto
            if (isset($service['variant_id'])) {
                $variant = \App\Models\ServiceVariants::find($service['variant_id']);
                $variantName = $variant ? $variant->name : 'Vidrio';
            }

            // Obtener precios del detalle de servicios si están disponibles
            $serviceDetail = $servicesDetail[$index] ?? [];
            $pergolaPrecio = $serviceDetail['pvp_pergola'] ?? 0;
            $cuadriculaPrecio = $serviceDetail['pvp_cuadricula'] ?? 0;

            // Si no tenemos el detalle, calcular como fallback
            if (empty($serviceDetail)) {
                $pergola = new static($inputs);
                $pergola->calcular();
                $pergolaPrecio = $pergola->pvp_pergola;
            }

            // Agregar servicio de pérgola
            $servicios[] = [
                'tipo' => 'Pergola de ' . $variantName,
                'medidas' => [
                    'medidaA' => $inputs['medidaA'] ?? 0,
                    'medidaB' => $inputs['medidaB'] ?? 0,
                    'alto' => $inputs['alto'] ?? 0,
                    'area' => ($inputs['medidaA'] ?? 0) * ($inputs['medidaB'] ?? 0),
                ],
                'precio' => $pergolaPrecio
            ];

            // Agregar cuadrícula si aplica y tiene precio
            if (!empty($service['selected_cuadricula']) && $service['selected_cuadricula'] !== 'ninguna' && $cuadriculaPrecio > 0) {
                $cuadriculaTipo = ucfirst(str_replace('_', ' ', $service['selected_cuadricula']));
                
                $medidaACuad = $inputs['medidaACuadricula'] ?? $inputs['medidaACuadriculaTrama'] ?? 0;
                $medidaBCuad = $inputs['medidaBCuadricula'] ?? $inputs['medidaBCuadriculaTrama'] ?? 0;
                $altoCuad = $inputs['altoCuadricula'] ?? $inputs['altoCuadriculaTrama'] ?? 0;
                
                $servicios[] = [
                    'tipo' => $cuadriculaTipo,
                    'medidas' => [
                        'medidaA' => $medidaACuad,
                        'medidaB' => $medidaBCuad,
                        'alto' => $altoCuad,
                        'area' => $medidaACuad * $medidaBCuad,
                    ],
                    'precio' => $cuadriculaPrecio // ✅ Usar precio real de la cuadrícula
                ];
            }
        }

        return $servicios;
    }

    /**
     * Procesa un solo servicio para el PDF (compatibilidad)
     */
    private function processSingleServiceForPDF(): array
    {
        // Obtener nombre de la variante
        $variantName = 'Vidrio'; // Valor por defecto
        if (isset($this->data['variant_id'])) {
            $variant = \App\Models\ServiceVariants::find($this->data['variant_id']);
            $variantName = $variant ? $variant->name : 'Vidrio';
        }

        // Obtener precios del detalle del servicio si están disponibles
        $serviceDetail = $this->data['service_detail'] ?? [];
        $pergolaPrecio = $serviceDetail['pvp_pergola'] ?? $this->pvp_pergola;
        $cuadriculaPrecio = $serviceDetail['pvp_cuadricula'] ?? 0;

        // Preparar servicios cotizados
        $servicios = [
            [
                'tipo' => 'Pergola de ' . $variantName,
                'medidas' => [
                    'medidaA' => $this->medidaA,
                    'medidaB' => $this->medidaB,
                    'alto' => $this->alto,
                    'area' => $this->area,
                ],
                'precio' => $pergolaPrecio
            ]
        ];

        // Agregar cuadrícula si fue seleccionada y tiene precio
        if (!empty($this->data['selected_cuadricula']) && $this->data['selected_cuadricula'] !== 'ninguna' && $cuadriculaPrecio > 0) {
            $cuadriculaTipo = ucfirst(str_replace('_', ' ', $this->data['selected_cuadricula']));
            
            // Obtener medidas de cuadrícula
            $medidaACuad = $this->data['medidaACuadricula'] ?? $this->medidaACuadriculaTrama ?? 0;
            $medidaBCuad = $this->data['medidaBCuadricula'] ?? $this->medidaBCuadriculaTrama ?? 0;
            $altoCuad = $this->data['altoCuadricula'] ?? $this->altoCuadriculaTrama ?? 0;
            
            $servicios[] = [
                'tipo' => $cuadriculaTipo,
                'medidas' => [
                    'medidaA' => $medidaACuad,
                    'medidaB' => $medidaBCuad,
                    'alto' => $altoCuad,
                    'area' => $medidaACuad * $medidaBCuad,
                ],
                'precio' => $cuadriculaPrecio // ✅ Usar precio real de la cuadrícula
            ];
        }

        return $servicios;
    }

    public function obtenerPDFOrdenProduccion(): string
    {
        $materiales = $this->obtenerDetalleMateriales();
        
        // Obtener información de columnas con colores si está disponible
        $columnasInfo = [];
        if (isset($this->data['columnas']) && is_array($this->data['columnas'])) {
            $columnasInfo = $this->data['columnas'];
        }
        
        // Obtener colores de columnas desde los inputs
        $coloresColumnas = [];
        if (isset($this->data['colores_columnas']) && is_array($this->data['colores_columnas'])) {
            $coloresColumnas = $this->data['colores_columnas'];
        }
        
        // Si no tenemos información detallada de columnas pero sí tenemos colores,
        // crear estructura básica con la información disponible
        if (empty($columnasInfo) && !empty($coloresColumnas)) {
            $columnasInfo = [];
            for ($i = 0; $i < $this->n_columnas; $i++) {
                $columnasInfo[] = [
                    'numero' => $i + 1,
                    'color' => $coloresColumnas[$i] ?? 'azul',
                    'altura' => $this->alto,
                    'x' => null,
                    'y' => null,
                    'observaciones' => ''
                ];
            }
        } else {
            // Agregar colores a la información existente de columnas
            foreach ($columnasInfo as $index => &$columna) {
                $columna['color'] = $coloresColumnas[$index] ?? 'azul';
                $columna['numero'] = $columna['numero'] ?? ($index + 1);
            }
        }
        
        $data = [
            'materiales' => $materiales,
            'medidas' => [
                'medidaA' => $this->medidaA,
                'medidaB' => $this->medidaB,
                'alto' => $this->alto,
                'area' => $this->area,
                'n_columnas' => $this->n_columnas,
                'n_bajantes' => $this->n_bajantes,
                'anillos' => $this->anillos,
            ],
            'columnas' => $columnasInfo,
            'extras' => [
                'estrategia_andamios' => $this->alquilar_andamios,
                'nota_pago_por_dia' => $this->pagar_dia_pergola
            ], 
            'titulo' => [
                'titulo_servicio' => 'Pergola de Vidrio'
            ],
            'cotizacion' => [
                'numero_cotizacion' => $this->data['quotation_id'] ?? 'COT-' . now()->timestamp,
                'fecha_emision' => now()->format('d/m/Y'),
                'fecha_vencimiento' => now()->addDays(30)->format('d/m/Y'),
                'fecha_orden' => now()->format('d/m/Y H:i:s'),
            ],
            'cliente' => [
                'nombre' => $this->data['client_name'] ?? 'Cliente no especificado',
                'dni' => $this->data['client_dni'] ?? '',
                'telefono' => $this->data['client_phone'] ?? '',
                'provincia' => $this->data['client_province'] ?? '',
            ],
            'tiempo_estimado' => [
                'dias' => round($this->tiempoDias, 1),
                'meses' => round($this->tiempoMeses, 2),
            ]
        ];

        $pdf = Pdf::loadView('pdfs.orden-produccion', $data);

        $cotizacionId = $this->data['quotation_id'] ?? 'COT-' . now()->timestamp;
        $path = 'pdf/orden_produccion/pergolas/orden_produccion_' . $cotizacionId . '_' . now()->timestamp . '.pdf';
        $fullPath = storage_path('app/public/' . $path);

        // Crear la carpeta si no existe
        $directory = dirname($fullPath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $pdf->save($fullPath);
        return $path;
    }
}
