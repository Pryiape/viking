<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Build extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'sujet',
        'description',
        'is_public',
        'talent_tree', 
    ];
    protected $casts = [
        'talent_tree' => 'array', // Cast talent_tree to array
    ];

    /**
     * Get the user that owns the build.
     */
    public function user()
    {

        return $this->belongsTo(User::class);
    }
}
