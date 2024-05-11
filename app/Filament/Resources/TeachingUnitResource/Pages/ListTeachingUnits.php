<?php

namespace App\Filament\Resources\TeachingUnitResource\Pages;

use App\Filament\Resources\TeachingUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeachingUnits extends ListRecords
{
    protected static string $resource = TeachingUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
