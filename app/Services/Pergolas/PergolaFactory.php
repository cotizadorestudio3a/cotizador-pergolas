<?php
namespace App\Services\Pergolas;

class PergolaFactory
{
    public static function crear(int $tipo, array $data): PergolaBase
    {
        return match ($tipo) {
            1 => new PergolaVidrio($data),
                // 'trama' => new PergolaTrama($data),
            default => throw new Exception("Tipo de pérgola desconocido")
        };
    }
}
