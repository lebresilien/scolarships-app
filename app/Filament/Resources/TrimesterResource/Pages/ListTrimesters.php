<?php

namespace App\Filament\Resources\TrimesterResource\Pages;

use App\Filament\Resources\TrimesterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrimesters extends ListRecords
{
    protected static string $resource = TrimesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
