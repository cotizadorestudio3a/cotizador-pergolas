<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'total',
        'iva',
        'pvp'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'iva' => 'decimal:2',
        'pvp' => 'decimal:2',
    ];

    /**
     * Relación con el cliente
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relación con el usuario (vendor)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con los items de la cotización
     */
    public function quotationItems(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }
}