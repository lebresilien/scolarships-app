<?php

namespace App\Filament\Resources\ClassroomResource\Pages;

use App\Filament\Resources\ClassroomResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class NoteClassroom extends Page
{
    use InteractsWithRecord;

    protected static string $resource = ClassroomResource::class;

    protected static string $view = 'filament.pages.note-page';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
