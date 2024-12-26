<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Academic extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'status'
    ];
    
    public function inscriptions() {
        return $this->hasMany(Inscription::class);
    }

    public function sequences() {
        return $this->hasMany(Sequence::class);
    }

    public function policies() {
        return $this->hasMany(ClassroomStudent::class);
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'status' => 'boolean'
    ];
}
