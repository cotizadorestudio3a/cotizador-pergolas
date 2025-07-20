<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Services extends Model
{
    use HasFactory;

    public function serviceVariants(): BelongsToMany
    {
        return $this->belongsToMany(ServiceVariants::class, 'service_variants', 'service_id');
    }

    /**
     * Relación con los items de cotización
     */
    public function quotationItems(): HasMany
    {
        return $this->hasMany(QuotationItem::class, 'service_id');
    }
}
