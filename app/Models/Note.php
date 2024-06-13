<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'sequence_id',
        'value',
        'classroom_student_id',
        'course_id'
    ];

    public function policy() {
        return $this->hasMany(ClassroomStudent::class);
    }
}
