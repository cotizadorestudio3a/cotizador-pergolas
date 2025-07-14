<?php

namespace App\Services\Cuadriculas;

use App\Services\Cuadriculas\CuadriculaBase;
use App\Services\Cuadriculas\CuadriculaTrama;

class CuadriculaFactory
{
    public static function crear(string $tipo, array $data): CuadriculaBase
    {
        return match ($tipo) {
            'cuadricula' => new Cuadricula($data),
            'cuadricula_trama' => new CuadriculaTrama($data),
            default => throw new Exception("Tipo de cuadricula desconocido")
        };
    }
}
