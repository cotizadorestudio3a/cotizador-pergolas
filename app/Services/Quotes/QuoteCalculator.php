<?php

namespace App\Services\Quotes;

use App\Services\Cuadriculas\CuadriculaFactory;
use App\Services\Pergolas\PergolaFactory;

class QuoteCalculator
{
    private const IVA_PERCENTAGE = 0.15;
    private const TIPOS_CUADRICULA = ['cuadricula', 'cuadricula_trama'];

    public function calculateTotal(array $services, array $inputsPorServicio): array
    {
        $pvp_total = 0;
        $total_per_service = [];

        foreach ($services as $servicio) {
            $inputs = $inputsPorServicio[$servicio['input_index']];
            
            // Calcular pérgola
            $pergola = PergolaFactory::crear($servicio['variant_id'], $inputs);
            $pergola_total = $pergola->calcular();
            $pvp_total += $pergola_total['pvp_pergola'];

            $service_detail = [
                'service_id' => $servicio['service_id'], 
                'variant_id' => $servicio['variant_id'] ?? null,
                'selected_cuadricula' => $servicio['selected_cuadricula'] ?? null,
                'column_color' => $servicio['color'] ?? $servicio['selected_color'] ?? null,
                'pvp_pergola' => $pergola_total['pvp_pergola'],
                'pvp_cuadricula' => 0, 
                'inputs' => $inputs, // Agregar inputs para referencia
            ];

            // Calcular cuadrícula si aplica
            if (in_array($servicio['selected_cuadricula'], self::TIPOS_CUADRICULA)) {
                $cuadricula = CuadriculaFactory::crear($servicio['selected_cuadricula'], $inputs);
                $cuadricula_total = $cuadricula->calcular();
                $pvp_total += $cuadricula_total['pvp_cuadricula'];
                $service_detail['pvp_cuadricula'] = $cuadricula_total['pvp_cuadricula'];
            }

            // Calcular total del servicio individual
            $service_detail['pvp_total_servicio'] = $service_detail['pvp_pergola'] + $service_detail['pvp_cuadricula'];
            
            $total_per_service[$servicio['input_index']] = $service_detail;
        }

        $iva = round($pvp_total * self::IVA_PERCENTAGE, 2);
        $pvp = round($pvp_total, 2);
        $total = $pvp + $iva;

        return [
            'pvp' => $pvp,
            'iva' => $iva,
            'total' => $total,
            'services_detail' => $total_per_service
        ];
    }

    /**
     * Valida los requisitos mínimos para realizar un cálculo
     */
    public function validateCalculationRequirements(?int $clientId, array $services): array
    {
        $errors = [];

        if (!$clientId) {
            $errors['client_id'] = 'Por favor, selecciona un cliente antes de calcular el total.';
        }
        
        if (empty($services)) {
            $errors['added_services'] = 'No hay servicios agregados para calcular.';
        }

        return $errors;
    }
}
