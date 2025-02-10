<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'name',
        'description',
        'teaching_unit_id',
        'coefficient',
        'teacher_id',
        'classroom_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d'
    ];

   /*  public function groups() {
        return $this->belongsToMany(Group::class, 'group_course');
    } */

    public function teachingUnit() {
        return $this->belongsTo(TeachingUnit::class);
    }

    public function notes() {
        return $this->hasMany(Note::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function teacher(): BelongsTo {
        return $this->belongsTo(Teacher::class);
    }
}
