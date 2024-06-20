<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'classroom_student_id',
        'course_id',
        'day',
        'justify_hour',
        'justify',
        'status'
    ];

    public function policy() {
        return $this->belongsTo(ClassroomStudent::class);
    }
}
