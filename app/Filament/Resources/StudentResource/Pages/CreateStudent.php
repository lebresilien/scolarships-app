<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

   /*  protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['matricule'] = '122';
        $data['academic_id'] = 12;
 
        return $data;
    } */

    protected function handleRecordCreation(array $data): Model {
        $data['matricule'] = 12;
        $student = Student::create($data);
        $student->classrooms()->attach($data['classroom_id'], ['academic_id' => $data['academic_id']]);
        return $student;
    }

    protected function getCreatedNotificationTitle(): ?string {
        return 'User registered';
    }

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    }

}
