<?php

namespace App\Filament\Resources\ClassroomStudentResource\Pages;

use App\Filament\Resources\ClassroomStudentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\ClassroomStudent;

class EditClassroomStudent extends EditRecord
{
    protected static string $resource = ClassroomStudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $policy = ClassroomStudent::findOrFail($record->policy);

        $policy->update($data);

        return $policy;
    }
}
