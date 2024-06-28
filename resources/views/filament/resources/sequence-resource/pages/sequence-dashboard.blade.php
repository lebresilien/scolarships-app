<x-filament-panels::page>
  
    @livewire(\App\Filament\Resources\SequenceResource\Widgets\StatsOverview::class, ['record' => $record])
 
    {{ $record }}
</x-filament-panels::page>
