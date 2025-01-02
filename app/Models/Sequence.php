<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{ BelongsTo, HasMany };

class Sequence extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'academic_id',
        'name',
        'status',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public function academic(): BelongsTo {
        return $this->belongsTo(Academic::class);
    }

    public function notes(): HasMany {
        return $this->hasMany(Note::class);
    }
}
