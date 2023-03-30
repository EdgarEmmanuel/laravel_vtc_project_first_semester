<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chauffeur extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone_number',
        'matricule',
        'password',
        'pays',
        'ville'
    ];



    public function voiture(): HasOne
    {
        return $this->hasOne(Voiture::class);
    }
}
