<?php

namespace App\Filament\Resources\TrimesterResource\Pages;

use App\Filament\Resources\TrimesterResource;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Forms;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use App\Filament\Traits\ActiveYear;
use App\Models\{ Classroom, Student, Academic, ClassroomStudent };
use Illuminate\Database\Eloquent\Builder;

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
                ->label('Bulletin')
                ->color('primary')
                ->icon('heroicon-s-cloud-arrow-down')
                ->url(fn ($record) => route('trimester-report', ['student' => $record, 'trimester' => $this->record]))
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('matricule'),
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
        ];
    }

    public function getTableFilters(): array
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
