<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\{ Transaction, Student, ClassroomStudent };
use Illuminate\Database\Eloquent\Model;
use App\Filament\Traits\ActiveYear;
use Illuminate\Support\Facades\DB;

class CreateStudent extends CreateRecord
{
    use ActiveYear;

    protected static string $resource = StudentResource::class;

    protected function handleRecordCreation(array $data): Model {
        
        $data['matricule'] = Student::generateUniqueMatricule();
        
        $model = DB::transaction(function () use ($data) {
            
            $student = Student::create($data);

            $policy = ClassroomStudent::create([
                'student_id' => $student->id,
                'classroom_id' => $data['classroom_id'],
                'academic_id' => $this->active()->id,
                'state' => $data['state']
            ]);

           /* $student->classrooms()->attach($data['classroom_id'], 
            [
                'academic_id' => $this->active()->id,
                'state' => $data['state']
            ]); */

            $transaction = Transaction::create([
                'classroom_student_id' => $policy->id,
                'name' => 'Inscription',
                'amount' => $data['amount']
            ]);

            return $student;
        });

        return $model;
    }

    protected function getCreatedNotificationTitle(): ?string {
        return 'Apprenant Enregistré';
    }

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    }

}
