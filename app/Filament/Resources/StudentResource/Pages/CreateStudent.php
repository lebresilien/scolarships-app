<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\{Transaction, Student};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Filament\Traits\ActiveYear;

class CreateStudent extends CreateRecord
{
    use ActiveYear;

    protected static string $resource = StudentResource::class;

    protected function handleRecordCreation(array $data): Model {
        
        $data['matricule'] = Student::generateUniqueMatricule();
        
        $student = Student::create($data);
        $student->classrooms()->attach($data['classroom_id'], ['academic_id' => $this->active()->id]);
        return $student;
    }

    protected function getCreatedNotificationTitle(): ?string {
        return 'User registered';
    }

    protected function getRedirectUrl(): string {
        return $this->getResource()::getUrl('index');
    }

}
