<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absence extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'classroom_student_id',
        'course_id',
        'sequence_id',
        'day',
        'hour',
        'justify_hour',
        'justify',
        'status'
    ];

    public function policy() {
        return $this->belongsTo(ClassroomStudent::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function sequence() {
        return $this->belongsTo(Sequence::class);
    }
}
