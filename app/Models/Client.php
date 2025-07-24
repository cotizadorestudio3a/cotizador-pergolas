<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dni',
        'phone',
        'province'
    ];

    public function vendors(): BelongsToMany
    {
        // Relación muchos a muchos con User (vendedores)
        return $this->belongsToMany(User::class, 'client_user', 'client_id', 'user_id');
    }

    /**
     * Relación con las cotizaciones del cliente
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }
}
