<?php

namespace App\Filament\Resources\SequenceResource\Pages;

use App\Filament\Resources\SequenceResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class SequenceDashboard extends Page
{
    use InteractsWithRecord;

    protected static string $resource = SequenceResource::class;

    protected static string $view = 'filament.resources.sequence-resource.pages.sequence-dashboard';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }
}
