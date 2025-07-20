<?php

namespace App\Services\Quotes;

class QuoteServiceManager
{
    public function createServiceEntry(int $serviceId, string $color, int $variantId, string $selectedCuadricula, int $inputIndex): array
    {
        return [
            "service_id" => $serviceId,
            "color" => $color,
            "variant_id" => $variantId,
            "selected_cuadricula" => $selectedCuadricula,
            "input_index" => $inputIndex
        ];
    }

    public function initializeServiceInputs(): array
    {
        return [
            'medidaA' => null,
            'medidaB' => null,
            'alto' => null,
            'n_columnas' => null,
            'n_bajantes' => null,
            'anillos' => null,
            'medidaACuadricula' => null,
            'medidaBCuadricula' => null,
            'distanciaPalillaje' => null, 
            'altoCuadricula' => null,
            'colores_columnas' => []
        ];
    }

    public function addServiceToList(array &$addedServices, array &$inputsPorServicio, int $serviceId, string $color, int $variantId, string $selectedCuadricula): int
    {
        $inputIndex = count($inputsPorServicio);
        $inputsPorServicio[] = $this->initializeServiceInputs();

        $addedServices[] = $this->createServiceEntry(
            $serviceId, 
            $color, 
            $variantId, 
            $selectedCuadricula, 
            $inputIndex
        );

        return count($addedServices) - 1; // Return new active index
    }
}
