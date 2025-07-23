<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';

    protected $fillable = [
        'code',
        'name',
        'unit',
        'unit_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2'
    ];

    /**
     * Obtiene el precio de un material por su nombre
     */
    public static function getPriceByName(string $name): float
    {
        $material = static::where('name', $name)->first();
        return $material ? (float) $material->unit_price : 0.0;
    }

    /**
     * Obtiene el precio de un material por su código
     */
    public static function getPriceByCode(string $code): float
    {
        $material = static::where('code', $code)->first();
        return $material ? (float) $material->unit_price : 0.0;
    }

    /**
     * Obtiene todos los precios como array asociativo [nombre => precio]
     */
    public static function getAllPricesArray(): array
    {
        return static::pluck('unit_price', 'name')->toArray();
    }

    /**
     * Obtiene todos los precios como array asociativo [código => precio]
     */
    public static function getAllPricesByCode(): array
    {
        return static::pluck('unit_price', 'code')->toArray();
    }

    /**
     * Actualiza el precio de un material por su nombre
     */
    public static function updatePriceByName(string $name, float $price): bool
    {
        return static::where('name', $name)->update(['unit_price' => $price]) > 0;
    }

    /**
     * Actualiza el precio de un material por su código
     */
    public static function updatePriceByCode(string $code, float $price): bool
    {
        return static::where('code', $code)->update(['unit_price' => $price]) > 0;
    }
}
