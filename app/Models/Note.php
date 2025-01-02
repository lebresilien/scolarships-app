<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{ BelongsTo, HasMany };

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'sequence_id',
        'value',
        'classroom_student_id',
        'course_id'
    ];

    public function policy(): HasMany {
        return $this->hasMany(ClassroomStudent::class);
    }

    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }
}
