<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceVariants extends Model
{
    use HasFactory;

    public function service(): BelongsTo
    {
        return $this->belongsTo(Services::class);
    }

    /**
     * Relación con los items de cotización
     */
    public function quotationItems(): HasMany
    {
        return $this->hasMany(QuotationItem::class, 'service_variant_id');
    }
}
