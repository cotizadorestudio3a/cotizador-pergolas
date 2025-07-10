<?php

namespace App\Services\Cuadriculas;

use App\Services\Cuadriculas\CuadriculaBase; 

class CuadriculaFactory
{
    public static function crear(string $tipo, array $data): CuadriculaBase
    {
        return match ($tipo) {
            'cuadricula' => new Cuadricula($data),
                // 'trama' => new PergolaTrama($data),
            default => throw new Exception("Tipo de cuadricula desconocido")
        };
    }
}
