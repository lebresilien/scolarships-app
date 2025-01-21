<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'postal_address',
        'phone_number',
        'registration_number',
        'is_primary_school',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }
}
