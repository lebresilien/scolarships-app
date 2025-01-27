<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'building_id',
        'group_id',
        'user_id',
        'name',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public function building() {
        return $this->belongsTo(Building::class);
    } 

    public function group() {
        return $this->belongsTo(Group::class);
    } 

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function students() {
        return $this->belongsToMany(Student::class)->withPivot('academic_id', 'status', 'id');
    }

    public function notes() {
        return $this->hasMany(Note::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getCountStudentAttribute(): int
    {
        return $this->students()->where('academic_id', Academic::where('status', true)->first()->id)->count();
    }
}
