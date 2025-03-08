<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'specialization_id',
    ];

    // Ajoutez d'autres méthodes ou relations si nécessaire
}
