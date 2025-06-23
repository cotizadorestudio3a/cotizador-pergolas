<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    public function vendors()
    {
        return $this->belongsToMany(User::class,'client_user', 'user_id', 'client_id');
    }
}
