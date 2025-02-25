<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeachingUnit extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'group_id',
        'name',
        'description'
    ];

    //protected $with = ['courses'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function courses() {
        return $this->hasMany(Course::class);
    }

    public function getCoefficientAttribute(): int {
        $coeff = 0;
        foreach($this->courses as $course) {
            $coeff += $course->coefficient;
        }
        return $coeff;
    }

    public function getGroupNameAttribute(): string {
        return $this->name . ' (' . strtoupper($this->group->name) . ')';
    }
}
