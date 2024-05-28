<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Log;
use Filament\Resources\Components\Tab;
use App\Models\{Classroom, Student, Academic, ClassroomStudent, Transaction};
use App\Filament\Traits\ActiveYear;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Filament\Forms;
use Filament\Pages\Actions\CreateAction;
use Filament\Notifications\Notification;

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
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ActionGroup::make([
                Tables\Actions\Action::make('Editer')
                ->icon('heroicon-s-pencil-square')
                ->fillForm(fn (Student $record): array => [
                    'classroom_id' => $record->classrooms[0]->pivot->classroom_id,
                    'academic_id' => $record->classrooms[0]->pivot->academic_id,
                    'status' => $record->status,
                    'name' => $record->fname . ' ' . $record->fname,
                ])
                ->form([
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\Select::make('classroom_id')
                                ->label('Classe')
                                ->options(Classroom::query()->pluck('name', 'id'))
                                ->required(),
                            Forms\Components\Select::make('academic_id')
                                ->label('Année Académique')
                                ->options(Academic::query()->pluck('name', 'id'))
                                ->required(),
                            Forms\Components\Toggle::make('status'),
                            Forms\Components\TextInput::make('name')
                                ->label('Noms et Prénoms')
                                ->disabled()
                        ])
                ])
                ->action(function (array $data, Student $record): void {

                   $policy = ClassroomStudent::find($record->classrooms[0]->pivot->id);
                 
                   $policy->classroom_id = $data['classroom_id'];
                   $policy->academic_id = $data['academic_id'];
                   $policy->status = $data['status'];

                   $policy->save(); 
                  
                }),
                Tables\Actions\Action::make('Paiement')
                ->icon('heroicon-o-currency-dollar')
                ->fillForm(fn (Student $record): array => [
                    'fname' => $record->fname . ' ' . $record->fname,
                ])
                ->form([
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\TextInput::make('fname')
                                ->label('Noms et Prénoms')
                                ->columnSpan(2)
                                ->disabled(),
                            Forms\Components\TextInput::make('amount')
                                ->label('Montant')
                                ->numeric()
                                ->required(),
                            Forms\Components\TextInput::make('name')
                                ->label('Raison du paiement')
                                ->required()
                        ])
                ])
                ->action(function (array $data, Student $record): void {
                    Transaction::create([
                        'name' => $data['name'],
                        'amount' => $data['amount'],
                        'classroom_student_id' => $record->classrooms[0]->pivot->id
                    ]);
                    Notification::make()
                        ->success()
                        ->title('Opération réussie')
                        ->body('Versement a été enregistré.')
                        ->send();
                }),
                Tables\Actions\DeleteAction::make(),
            ])
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
            Tables\Columns\TextColumn::make('classrooms.name')
                ->label('Classe')
                ->searchable(),
            Tables\Columns\BadgeColumn::make('status')
            ->getStateUsing( function ($record){
                return $record->status ? 'Inscrit' : 'Démissionaire';
            })
            ->color(static function ($record): string {
                return $record->status ? 'success' : 'danger';
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