<?php

namespace App\Filament\Resources\AcademicResource\Pages;

use App\Filament\Resources\AcademicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Log;

class ListAcademics extends ListRecords
{
    protected static string $resource = AcademicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->successRedirectUrl(route('filament.dashboard.resources.academics.index'))
        ];
    }
}
