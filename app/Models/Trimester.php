<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class Trimester extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'name',
        'academic_id',
        'sequence',
        'is_year'
    ];

    protected $casts = [
        'academic_id' => 'string',
        'name' => 'string',
        'sequence' => 'array',
        'is_year' => 'boolean'
    ];

    public function sequence(): HasMany {
        return $this->hasMany(Sequence::class);
    }

    public function academic(): BelongsTo {
        return $this->belongsTo(Academic::class);
    }

    public function notes($seqs) {
        return Note::whereIn('sequence_id', $seqs);
    }
}
