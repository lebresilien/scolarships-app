<?php

namespace App\Filament\Resources\TrimesterResource\Pages;

use App\Filament\Resources\TrimesterResource;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use App\Filament\Traits\ActiveYear;
use Illuminate\Support\Facades\Log;

class TrimesterDashboard extends Page implements Tables\Contracts\HasTable
{
    use ActiveYear;
    use InteractsWithRecord;
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = TrimesterResource::class;

    protected static string $view = 'filament.resources.trimester-resource.pages.trimester-dashboard';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        Log::info($this->record);
    }
}
