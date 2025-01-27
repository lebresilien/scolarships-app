<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone_number'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public function courses(): HasMany {
        return $this->hasMany(Course::class);
    }
}
