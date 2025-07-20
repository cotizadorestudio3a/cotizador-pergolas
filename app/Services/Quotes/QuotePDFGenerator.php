<?php

namespace App\Services\Quotes;

use App\Services\Cuadriculas\CuadriculaFactory;
use App\Services\Pergolas\PergolaFactory;

class QuotePDFGenerator
{
    private const TIPOS_CUADRICULA = ['cuadricula', 'cuadricula_trama'];

    public function generateAllPDFs(array $services, array $inputsPorServicio): array
    {
        $pdfs_generados = [];

        foreach ($services as $servicio) {
            $servicePDFs = $this->generatePDFForService($servicio, $inputsPorServicio);
            $pdfs_generados = array_merge($pdfs_generados, $servicePDFs);
        }

        return $pdfs_generados;
    }
    
    public function generatePDFForService(array $servicio, array $inputsPorServicio): array
    {
        $pdfs = [];
        $inputs = $inputsPorServicio[$servicio['input_index']];

        // Generar PDF de pérgola
        $pergola = PergolaFactory::crear($servicio['service_id'], $inputs);
        $pergola->calcular();
        $pdfs[] = [
            'titulo' => 'Orden Producción Pérgola',
            'path' => $pergola->obtenerPDFOrdenProduccion(),
        ];

        // Generar PDF de cuadrícula si aplica
        if (in_array($servicio['selected_cuadricula'], self::TIPOS_CUADRICULA)) {
            $cuadricula = CuadriculaFactory::crear($servicio['selected_cuadricula'], $inputs);
            $cuadricula->calcular();
            $pdfs[] = [
                'titulo' => 'Orden Producción Cuadrícula',
                'path' => $cuadricula->obtenerPDFOrdenProduccion(),
            ];
        }

        return $pdfs;
    }
}
