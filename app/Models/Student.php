<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, softDeletes;
    
    protected $fillable = [
        'matricule',
        'fname',
        'lname',
        'sexe',
        'father_name',
        'mother_name',
        'fphone',
        'mphone',
        'born_at',
        'born_place',
        'allergy',
        'logo',
        'description',
        'quarter'
    ];

    protected $dates = [ 'deleted_at' ];

    protected $with = ['classrooms'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public function classrooms() {
        return $this->belongsToMany(Classroom::class)->withPivot('academic_id', 'status', 'id');
    }

    public function notes() {
        return $this->hasMany(Note::class);
    }

    public static function generateUniqueMatricule(): string
    {
        $currentAcademicYear = \App\Models\Academic::where('status', true)->first();
        $prefix = explode('-', $currentAcademicYear->name);
        $school = 'PIG'; // You can customize the prefix
        $base = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        $matricule = $prefix[0] . $school . $base;

        // Check for uniqueness and regenerate if needed
        while (Student::where('matricule', $matricule)->exists()) {
            $base = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
            $matricule = $prefix[0] . $school . $base;
        }

        return $matricule;
    }

    public function getStatusAttribute()
    {
        return $this->classrooms()->where('academic_id' , \App\Models\Academic::where('status', true)->first()->id)->first()->pivot->status;
    }

    public function getCurrentClassroomAttribute(): string
    {
        return $this->classrooms()->where('academic_id' , \App\Models\Academic::where('status', true)->first()->id)->first()->name;
    }

    public function getCurrentAmountAttribute(): int
    {
        $amount = 0;

        $policy_id = $this->classrooms()->where('academic_id' , \App\Models\Academic::where('status', true)->first()->id)->first()->pivot->id;
        $policy = \App\Models\ClassroomStudent::find($policy_id);
        
        foreach($policy->transactions as $trx) {
            $amount += $trx->amount;
        }
        
        return $amount;
    }
}
