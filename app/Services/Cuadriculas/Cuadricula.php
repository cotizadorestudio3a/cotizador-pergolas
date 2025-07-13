<?php

namespace App\Services\Cuadriculas;

use Barryvdh\DomPDF\Facade\Pdf;

class Cuadricula extends CuadriculaBase
{

    // Inputs cuadricula
    public $medidaACuadricula;
    public $medidaBCuadricula;
    public $distanciaPalillajeCuadricula;
    public $altoCuadricula;


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

    // Totales cuadricula
    public $costo_total_cuadricula;
    public $margen_negociacion_cuadricula;
    public $margen_imprevistos_cuadricula;
    public $margen_utilidad_cuadricula;
    public $pvp_cuadricula;
    public $iva_cuadricula;
    public $total;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->medidaACuadricula = $data['medidaA'];
        $this->medidaBCuadricula = $data['medidaB'];
        $this->distanciaPalillajeCuadricula = $data['distanciaPalillaje'];
        $this->altoCuadricula = $data['alto'];
    }

    public function calcular(): array
    {
        $this->calcularDimensionesCuadricula();
        $this->calcularCantidadesCuadricula();
        $this->calcularUnidadesCuadricula();
        $this->definirPreciosCuadricula();
        $this->calcularTotalesCuadricula();

        return [
            'pvp_cuadricula' => $this->pvp_cuadricula,
            'total_cuadricula' => $this->total,
        ];
    }


    private function calcularDimensionesCuadricula()
    {
        $this->numero_cuadriculas = ceil(min($this->medidaACuadricula, $this->medidaBCuadricula) / $this->distanciaPalillajeCuadricula) + 1;
        $this->largo_cuadriculas = max($this->medidaACuadricula, $this->medidaBCuadricula);
        $this->vigas_cubierta_cuadricula = ceil(max($this->medidaACuadricula, $this->medidaBCuadricula) / 0.55) + 1;
        $this->largo_vigas_cuadricula = min($this->medidaBCuadricula, $this->medidaACuadricula);
        $this->area_cuadricula = $this->medidaBCuadricula * $this->medidaACuadricula;

        return [
            "numero_cuadriculas" => $this->numero_cuadriculas,
            "largo_cuadriculas" => $this->largo_cuadriculas,
            "vigas_cubierta_cuadricula" => $this->vigas_cubierta_cuadricula,
            "largo_vigas_cuadricula" => $this->largo_vigas_cuadricula,
            "area_cuadricula" => $this->area_cuadricula
        ];
    }

    private function calcularCantidadesCuadricula()
    {
        $this->cantidad_cuadricula = $this->numero_cuadriculas * $this->largo_cuadriculas;
        $this->cantidad_tornillos_cuadricula = $this->numero_cuadriculas * $this->vigas_cubierta_cuadricula;
        $this->cantidad_tornillos_t = ($this->vigas_cubierta_cuadricula * $this->largo_vigas_cuadricula) / 1;
        $this->cantidad_t_cuadricula = $this->vigas_cubierta_cuadricula * $this->largo_vigas_cuadricula;
        $this->cantidad_mano_de_obra_cuadricula = $this->area_cuadricula;

        return [
            "cantidad_cuadricula" => $this->cantidad_cuadricula,
            "cantidad_tornillos_cuadricula" => $this->cantidad_tornillos_cuadricula,
            "cantidad_tornillos_t" => $this->cantidad_tornillos_t,
            "cantidad_t_cuadricula" => $this->cantidad_t_cuadricula,
            "cantidad_mano_de_obra_cuadricula" => $this->cantidad_mano_de_obra_cuadricula
        ];
    }

    private function calcularUnidadesCuadricula()
    {
        $this->unidades_cuadricula = $this->cantidad_cuadricula / 6.4;
        $this->unidades_t_cuadricula = $this->cantidad_t_cuadricula / 6.4;

        return [
            "unidades_cuadricula" => $this->unidades_cuadricula,
            "unidades_t_cuadricula" => $this->unidades_t_cuadricula
        ];
    }

    private function calcularTotalesCuadricula()
    {
        $this->total_cuadricula = $this->precio_cuadricula * ($this->numero_cuadriculas * $this->largo_cuadriculas) / 6.4;
        $this->total_tornillos_cuadricula = $this->precio_tornillos_cuadricula * $this->numero_cuadriculas * $this->vigas_cubierta_cuadricula;
        $this->total_tornillos_t = $this->precio_tornillos_t * ($this->vigas_cubierta_cuadricula * $this->largo_vigas_cuadricula);
        $this->total_t_cuadricula = $this->precio_t_cuadricula * $this->vigas_cubierta_cuadricula * $this->largo_vigas_cuadricula;
        $this->total_mano_de_obra = $this->precio_mano_de_obra_cuadricula * $this->area_cuadricula;

        $this->costo_total_cuadricula = (
            $this->total_cuadricula + $this->total_tornillos_cuadricula + $this->total_tornillos_t +
            $this->total_t_cuadricula + $this->total_mano_de_obra
        );

        $this->margen_negociacion_cuadricula = round($this->costo_total_cuadricula * 0.08, 2);
        $this->margen_imprevistos_cuadricula = round($this->costo_total_cuadricula * 0.04, 2);
        $this->margen_utilidad_cuadricula = round($this->costo_total_cuadricula * 0.44, 2);

        // Cálculo del PVP de la cuadrícula (sin IVA)
        $this->pvp_cuadricula = round($this->costo_total_cuadricula +
            $this->margen_negociacion_cuadricula +
            $this->margen_imprevistos_cuadricula +
            $this->margen_utilidad_cuadricula, 2);

        $this->total = $this->pvp_cuadricula;
    }

    private function definirPreciosCuadricula()
    {
        $this->precio_cuadricula = 10;
        $this->precio_tornillos_cuadricula = 0.06;
        $this->precio_tornillos_t = 0.06;
        $this->precio_t_cuadricula = 5.42;
        $this->precio_mano_de_obra_cuadricula = 3;
    }

    private function obtenerDetalleMateriales()
    {
        $materiales = [
            "Cuadricula" => 'cuadricula',
            "Tornillos Cuadricula" => 'tornillos_cuadricula',
            "Tornillos T" => 'tornillos_t',
            "T" => 't_cuadricula',
            "Mano de obra" => 'mano_de_obra_cuadricula'
        ];

        $usarCantidadParaTotal = [
            'tornillos_cuadricula',
            'tornillos_t',
            'mano_de_obra_cuadricula', 
            't_cuadricula'
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
        return '';
    }

    public function obtenerPDFOrdenProduccion(): string
    {
        $this->calcular();

        $materiales = $this->obtenerDetalleMateriales();
        $data = [
            'materiales' => $materiales,
            'medidas' => [
                'medidaA' => $this->medidaACuadricula,
                'medidaB' => $this->medidaBCuadricula,
                'alto' => $this->altoCuadricula,
                'area' => $this->area_cuadricula,
            ],
        ];

        $pdf = Pdf::loadView('pdfs.orden-produccion', $data);
        $path = 'pdf/orden_produccion/cuadriculas/orden_produccion_' . now()->timestamp . '.pdf';
        $pdf->save(storage_path('app/public/' . $path));

        return $path;
    }
}
