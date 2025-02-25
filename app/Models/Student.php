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

    //protected $with = ['classrooms'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public function classrooms() {
        return $this->belongsToMany(Classroom::class)->withPivot('academic_id', 'status', 'id', 'state');
    }

    public function notes() {
        return $this->hasMany(Note::class);
    }

    public static function generateUniqueMatricule(): string
    {
        $currentAcademicYear = Academic::where('status', true)->first();
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
        return $this->classrooms()->where('academic_id', $this->academic()->id)->first()->pivot->status;
    }

    public function getFullNameAttribute()
    {
        return $this->fname . ' ' . $this->lname;
    }

    public function getStateAttribute()
    {
        return $this->classrooms()->where('academic_id', $this->academic()->id)->first()->pivot->state;
    }

    public function getPolicyAttribute()
    {
        return $this->classrooms()->where('academic_id', $this->academic()->id)->first()->pivot->id;
    }

    public function getCurrentClassroomAttribute(): Classroom
    {
        return $this->classrooms()->where('academic_id', $this->academic()->id)->first();
    }

    public function getCurrentAmountAttribute(): int
    {
        $amount = 0;

        $policy_id = $this->classrooms()->where('academic_id', $this->academic()->id)->first()->pivot->id;
        $policy = ClassroomStudent::find($policy_id);
        
        foreach($policy->transactions as $trx) {
            $amount += $trx->amount;
        }
        
        return $amount;
    }

    public function transactions() {
        $data = $this->hasMany(ClassroomStudent::class)
                    ->where('academic_id', $this->academic()->id)
                    ->first();
        return $data ? $data->transactions() : [];
    }

    public function absences() {
        $data = $this->hasMany(ClassroomStudent::class)
                    ->where('academic_id', $this->academic()->id)
                    ->first();
        return $data ? $data->absences() : [];
    }

    public function academic() {
        return Academic::where('status', true)->first();
    }
}
