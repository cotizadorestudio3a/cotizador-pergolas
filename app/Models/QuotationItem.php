<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'service_id',
        'service_variant_id',
        'calculated_price',
        'color',
        'cuadricula_type',
        'inputs',
        'total'
    ];

    protected $casts = [
        'calculated_price' => 'decimal:2',
        'total' => 'decimal:2',
        'inputs' => 'array'
    ];

    /**
     * Relación con la cotización
     */
    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    /**
     * Relación con el servicio
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Services::class, 'service_id');
    }

    /**
     * Relación con la variante del servicio
     */
    public function serviceVariant(): BelongsTo
    {
        return $this->belongsTo(ServiceVariants::class, 'service_variant_id');
    }
}