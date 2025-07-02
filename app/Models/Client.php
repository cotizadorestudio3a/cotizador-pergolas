<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dni',
        'phone',
        'province'
    ];

    public function vendors()
    {
        // Correct order: the current model's key (client_id) comes first, then related model's key (user_id)
        return $this->belongsToMany(User::class, 'client_user', 'client_id', 'user_id');
    }
}
