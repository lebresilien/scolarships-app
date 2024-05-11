<?php

namespace App\Filament\Resources\TeachingUnitResource\Pages;

use App\Filament\Resources\TeachingUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeachingUnit extends EditRecord
{
    protected static string $resource = TeachingUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
