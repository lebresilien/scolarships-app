<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'section_id',
        'name',
        'description',
        'fees'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public function section() {
        return $this->belongsTo(Section::class);
    }

    public function classrooms() {
        return $this->hasMany(Classroom::class);
    }

    public function courses() {
        return $this->hasManyThrough(Course::class, Unit::class);
    }

    public function notes()
    {
        return $this->hasManyThrough(Note::class, Classroom::class);
    }

    public function teachings() {
        return $this->hasMany(TeachingUnit::class);
    }

}
