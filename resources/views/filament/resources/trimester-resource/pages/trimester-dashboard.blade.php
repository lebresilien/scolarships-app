<x-filament-panels::page>
    @livewire(\App\Filament\Resources\SequenceResource\Widgets\StatsOverview::class, ['record' => $record])
 
    {{ $this->table }} 
</x-filament-panels::page>
