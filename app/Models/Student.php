<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, softDeletes;
    
    protected $fillable = [
        'matricule',
        'fname',
        'lname',
        'sexe',
        'father_name',
        'mother_name',
        'fphone',
        'mphone',
        'born_at',
        'born_place',
        'allergy',
        'logo',
        'description',
        'quarter'
    ];

    protected $dates = [ 'deleted_at' ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public function classrooms() {
        return $this->belongsToMany(Classroom::class)->withPivot('academic_id');
    }

    public function notes() {
        return $this->hasMany(Note::class);
    }

}
