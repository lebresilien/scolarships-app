<?php

namespace App\Filament\Resources\AcademicResource\Pages;

use App\Filament\Resources\AcademicResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Academic;

class CreateAcademic extends CreateRecord
{
    protected static string $resource = AcademicResource::class;

    protected function getCreatedNotificationTitle(): ?string {
        return 'Academics created';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        Academic::where('id', '<>', $this->record->id)->update(['status' => false]);
    }

}
