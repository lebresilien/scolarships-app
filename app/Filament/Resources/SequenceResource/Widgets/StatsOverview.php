<?php

namespace App\Filament\Resources\SequenceResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class StatsOverview extends BaseWidget
{
    use InteractsWithRecord;

    public function mount(int | string $record): void
    {
        $this->record = $record;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Unique views', '192.1k'),
            Stat::make('Bounce rate', '21%'),
            Stat::make('Average time on page', '3:12'),
        ];
    }
}
