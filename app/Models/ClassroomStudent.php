<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomStudent extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'classroom_id', 'academic_id', 'status'];

    protected $table = 'classroom_student';
    
    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
