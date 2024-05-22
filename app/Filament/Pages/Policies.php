<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class Policies extends Page
{
    use InteractsWithRecord;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.policies';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);

        Log::info($this->record);
    }
}
