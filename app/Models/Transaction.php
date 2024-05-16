<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'classroom_student_id',
        'amount',
        'name',
    ];

    public function policies() {
        return $this->belongsTo(ClassroomStudent::class);
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];
}
