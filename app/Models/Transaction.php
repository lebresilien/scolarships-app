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

    public function policy() {
        return $this->belongsTo(ClassroomStudent::class, 'classroom_student_id', 'id');
    }

    public function getValueAttribute() {
      return $this->policy;
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];
}
