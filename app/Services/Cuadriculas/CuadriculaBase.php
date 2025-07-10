<?php

namespace App\Services\Cuadriculas;

abstract class CuadriculaBase
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    abstract public function calcular(): array;
    abstract public function obtenerPDFCotizacion(): string;
    abstract public function obtenerPDFOrdenProduccion(): string;
}
