<?php
namespace App\Services\Pergolas;

use Exception;

class PergolaFactory
{
    public static function crear(int $tipo, array $data): PergolaBase
    {
        return match ($tipo) {
            1 => new PergolaVidrio($data),        // variant_id = 1 (Vidrio)
            2 => new PergolaVidrio($data),        // variant_id = 2 (Policarbonato) 
            3 => new PergolaVidrio($data),        // variant_id = 3 (RH - Vidrio)
            4 => new CorintiaRHTejaAsfaltica($data), // variant_id = 4 (RH - Teja asfaltica)
            default => throw new Exception("Tipo de pérgola desconocido: $tipo")
        };
    }
}
