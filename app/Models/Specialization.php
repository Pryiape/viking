<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'class_id',
    ];

    /**
     * Get the class that owns the specialization.
     */
    public function class()
    {
        return $this->belongsTo(Classes::class);
    }
}
