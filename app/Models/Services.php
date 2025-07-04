<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;

    public function serviceVariants()
    {
        return $this->belongsToMany(ServiceVariants::class, 'service_variants', 'service_id');
    }
}
