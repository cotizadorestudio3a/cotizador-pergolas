<?php

namespace App\Services\Quotes;

class ColumnColorManager
{
    public function adjustColumnColors(array &$inputsPorServicio, int $servicioIndex, int $numColumnas, ?string $defaultColor = 'azul'): void
    {
        if ($numColumnas > 0) {
            $coloresExistentes = $inputsPorServicio[$servicioIndex]['colores_columnas'] ?? [];
            
            $coloresActualizados = [];
            for ($i = 0; $i < $numColumnas; $i++) {
                $coloresActualizados[$i] = $coloresExistentes[$i] ?? $defaultColor;
            }
            
            $inputsPorServicio[$servicioIndex]['colores_columnas'] = $coloresActualizados;
        } else {
            $inputsPorServicio[$servicioIndex]['colores_columnas'] = [];
        }
    }

    public function initializeColumnColors(array &$inputsPorServicio, int $servicioIndex): void
    {
        if (!isset($inputsPorServicio[$servicioIndex]['colores_columnas'])) {
            $inputsPorServicio[$servicioIndex]['colores_columnas'] = [];
        }
    }

    public function changeColumnColor(array &$inputsPorServicio, int $servicioIndex, int $columnaIndex, string $color): void
    {
        $this->initializeColumnColors($inputsPorServicio, $servicioIndex);
        $inputsPorServicio[$servicioIndex]['colores_columnas'][$columnaIndex] = $color;
    }
}
