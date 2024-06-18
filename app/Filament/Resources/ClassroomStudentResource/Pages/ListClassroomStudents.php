<?php

namespace App\Filament\Resources\ClassroomStudentResource\Pages;

use App\Filament\Resources\ClassroomStudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassroomStudents extends ListRecords
{
    protected static string $resource = ClassroomStudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
