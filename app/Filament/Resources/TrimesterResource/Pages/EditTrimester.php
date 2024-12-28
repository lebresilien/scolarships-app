<?php

namespace App\Filament\Resources\TrimesterResource\Pages;

use App\Filament\Resources\TrimesterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrimester extends EditRecord
{
    protected static string $resource = TrimesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
