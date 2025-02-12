<?php

namespace App\Filament\Resources\SequenceResource\Pages;

use App\Filament\Resources\SequenceResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use App\Models\{ Classroom, Student, Academic, ClassroomStudent, Transaction };
use App\Filament\Traits\ActiveYear;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Log;

class SequenceDashboard extends Page implements Tables\Contracts\HasTable
{
    use ActiveYear;
    use InteractsWithRecord;
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = SequenceResource::class;

    protected static string $view = 'filament.resources.sequence-resource.pages.sequence-dashboard';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function getTableQuery(): Builder
    {
        return Student::whereHas('classrooms', function($query) {
            $query->where('academic_id', $this->active()->id);
        });
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('pdf') 
                ->label('PDF')
                ->color('primary')
                ->icon('heroicon-s-cloud-arrow-down')
                ->url(fn ($record) => route('reports', ['student' => $record, 'seq' => $this->record])),
            
        ];
    }

    public function renderView($record)
    {
        return view('pdf.report', ['record' => $record]); 
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('matricule')
                ->searchable(),
            Tables\Columns\TextColumn::make('fname')
                ->label('Nom')
                ->searchable(),
            Tables\Columns\TextColumn::make('lname')
                ->label('Prénom')
                ->searchable(),
            Tables\Columns\TextColumn::make('sexe')
                ->getStateUsing( function ($record){
                    return $record->sexe ? 'H' : 'F';
                }),
            Tables\Columns\TextColumn::make('current_classroom.name')
                ->label('Classe')
                //->searchable()
        ];
    }

    public function getFilters(): array
    {
        return [
                Tables\Filters\Filter::make('classroom_id')
                    ->form([
                        Forms\Components\Select::make('value')
                        ->label('Classe')
                        ->options(Classroom::all()->pluck('name', 'id'))
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'],
                                fn (Builder $query, $value): Builder => $query->whereHas('classrooms', fn (Builder $query): Builder => $query->where('classroom_id', $value)->where('status', true)),
                            );
                    })
        ];
    }
}
