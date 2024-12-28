<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
