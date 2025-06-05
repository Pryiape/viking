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

    /**
     * The builds that belong to the talent.
     */
    public function builds()
    {
        return $this->belongsToMany(Build::class, 'build_talent');
    }
}
