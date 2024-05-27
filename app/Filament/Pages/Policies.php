<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;
use Filament\Resources\Components\Tab;
use App\Models\Student;
use App\Filament\Traits\ActiveYear;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Models\{Academic};
use Filament\Forms;

class Policies extends Page implements Tables\Contracts\HasTable
{

    use ActiveYear;
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.policies';

    public $students;

    protected $academicYear;

    protected function getTableQuery(): Builder
    {
        $this->academicYear = $this->active();

        return Student::whereHas('classrooms', function($query) {
            $query->where('academic_id', $this->academicYear->id);
        });
        //dd($s->get());
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
            Tables\Columns\TextColumn::make('classrooms.name')
                ->label('Classe')
                ->searchable(),
            Tables\Columns\BadgeColumn::make('status')
            ->getStateUsing( function ($record){
                return $record->status ? 'Inscrit' : 'Démissionaire';
            })
            ->color(static function ($record): string {
                if ($record->status) {
                    return 'success';
                }
                
                return 'danger';
            })
            
        ];
    }

   /*  public function mount()
    {
        $this->academicYear = $this->active();

        $students = Student::whereHas('classrooms', function($query) {
            $query->where('academic_id', $this->academicYear->id);
        });

        $this->students = $students->get();
    } */
    

}