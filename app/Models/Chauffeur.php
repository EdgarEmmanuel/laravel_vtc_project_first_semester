<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chauffeur extends Model
{
    use HasFactory;



    public function voiture(): HasOne
    {
        return $this->hasOne(Voiture::class);
    }
}
