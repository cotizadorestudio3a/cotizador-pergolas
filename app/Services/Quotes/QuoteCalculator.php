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

        foreach ($services as $servicio) {
            $inputs = $inputsPorServicio[$servicio['input_index']];
            
            // Calcular pérgola
            $pergola = PergolaFactory::crear($servicio['service_id'], $inputs);
            $pergola_total = $pergola->calcular();
            $pvp_total += $pergola_total['pvp_pergola'];

            // Calcular cuadrícula si aplica
            if (in_array($servicio['selected_cuadricula'], self::TIPOS_CUADRICULA)) {
                $cuadricula = CuadriculaFactory::crear($servicio['selected_cuadricula'], $inputs);
                $cuadricula_total = $cuadricula->calcular();
                $pvp_total += $cuadricula_total['pvp_cuadricula'];
            }
        }

        $iva = round($pvp_total * self::IVA_PERCENTAGE, 2);
        $pvp = round($pvp_total, 2);
        $total = $pvp + $iva;

        return [
            'pvp' => $pvp,
            'iva' => $iva,
            'total' => $total
        ];
    }

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
