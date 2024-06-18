<?php

namespace App\Filament\Resources\ClassroomStudentResource\Pages;

use App\Filament\Resources\ClassroomStudentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassroomStudent extends EditRecord
{
    protected static string $resource = ClassroomStudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
