<?php

namespace App\Services\Quotes;

use App\Services\Cuadriculas\CuadriculaFactory;
use App\Services\Pergolas\PergolaFactory;

class QuotePDFGenerator
{
    private const TIPOS_CUADRICULA = ['cuadricula', 'cuadricula_trama'];

    public function generateAllPDFs(array $services, array $inputsPorServicio, $quotation = null): array
    {
        $pdfs_generados = [];

        // 1. Generar UN SOLO PDF comercial con todos los servicios
        if ($quotation) {
            $commercialPDF = $this->generateCommercialPDF($services, $inputsPorServicio, $quotation);
            $pdfs_generados[] = $commercialPDF;
        }

        // 2. Generar PDFs de producción individuales para cada servicio
        foreach ($services as $servicio) {
            $productionPDFs = $this->generateProductionPDFsForService($servicio, $inputsPorServicio, $quotation);
            $pdfs_generados = array_merge($pdfs_generados, $productionPDFs);
        }

        return $pdfs_generados;
    }

    /**
     * Genera UN SOLO PDF comercial con todos los servicios de la cotización
     */
    private function generateCommercialPDF(array $services, array $inputsPorServicio, $quotation): array
    {
        // Usar el primer servicio como base para el PDF comercial
        $firstService = $services[0];
        $inputs = $inputsPorServicio[$firstService['input_index']];

        // Agregar información del cliente y cotización
        $inputs['quotation_id'] = 'COT-' . str_pad($quotation->id, 6, '0', STR_PAD_LEFT);
        $inputs['client_name'] = $quotation->client->name ?? '';
        $inputs['client_dni'] = $quotation->client->dni ?? '';
        $inputs['client_phone'] = $quotation->client->phone ?? '';
        $inputs['client_province'] = $quotation->client->province ?? '';
        $inputs['total'] = $quotation->total;
        $inputs['iva'] = $quotation->iva;
        $inputs['pvp'] = $quotation->pvp;

        // Preparar información de TODOS los servicios para el PDF
        $inputs['all_services'] = [];
        
        foreach ($services as $servicio) {
            $serviceInputs = $inputsPorServicio[$servicio['input_index']];
            
            $inputs['all_services'][] = [
                'service_id' => $servicio['service_id'],
                'variant_id' => $servicio['variant_id'] ?? null,
                'selected_cuadricula' => $servicio['selected_cuadricula'] ?? null,
                'color' => $servicio['color'] ?? $servicio['selected_color'] ?? 'azul',
                'inputs' => $serviceInputs
            ];
        }

        // Generar PDF comercial usando el primer servicio como base
        $pergola = PergolaFactory::crear($firstService['service_id'], $inputs);
        $pergola->calcular();
        
        return [
            'titulo' => 'Cotización Comercial',
            'path' => $pergola->obtenerPDFCotizacion(),
        ];
    }

    /**
     * Genera PDFs de producción para un servicio individual
     */
    private function generateProductionPDFsForService(array $servicio, array $inputsPorServicio, $quotation = null): array
    {
        $pdfs = [];
        $inputs = $inputsPorServicio[$servicio['input_index']];

        // Agregar información básica si hay cotización
        if ($quotation) {
            $inputs['quotation_id'] = 'COT-' . str_pad($quotation->id, 6, '0', STR_PAD_LEFT);
            $inputs['client_name'] = $quotation->client->name ?? '';
        }

        // Agregar información del servicio específico
        $inputs['service_variant_id'] = $servicio['variant_id'] ?? null;
        $inputs['selected_cuadricula'] = $servicio['selected_cuadricula'] ?? null;

        // Generar PDF de orden de producción de pérgola
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
