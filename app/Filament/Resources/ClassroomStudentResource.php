<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomStudentResource\Pages;
use App\Filament\Resources\ClassroomStudentResource\RelationManagers;
use App\Models\{Classroom, Student, Academic, ClassroomStudent, Transaction};
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;

class ClassroomStudentResource extends Resource
{

    protected static ?string $model = ClassroomStudent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static ?string $label = 'Inscriptions';

    protected static ?string $navigationGroup = 'Elèves';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->label('Noms et Prénoms')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('classroom_id')
                            ->label('Classe')
                            ->options(function ($record, Forms\Set $set) { 
                                if (! empty($record)) {
                                    $set('classroom_id', $record->current_classroom->id);
                                    $set('full_name', $record->full_name);
                                    $set('status', $record->status);
                                    $set('state', $record->state);
                                } 
                                return Classroom::query()->pluck('name', 'id');
                            })
                            ->required(),
                        Forms\Components\Select::make('academic_id')
                            ->label('Année Académique')
                            ->options(function ($record, Forms\Set $set) { 
                                if (! empty($record)) {
                                    $set('academic_id', $record->classrooms[0]->pivot->academic_id);
                                } 
                                return Academic::query()->pluck('name', 'id');
                            })
                            ->required(),
                        Forms\Components\Toggle::make('state')
                            ->label('Redouble'),
                        Forms\Components\Toggle::make('status')
                            ->label('Démissionnaire'),
                    ]) 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('matricule')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fname')
                    ->label('Noms')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lname')
                    ->label('Prénoms')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sexe')
                    ->getStateUsing( function ($record){
                        return $record->sexe ? 'H' : 'F';
                    }),
                Tables\Columns\TextColumn::make('current_classroom.name')
                    ->label('Classe'),
                Tables\Columns\TextColumn::make('current_amount')
                    ->label('Montant'),
                Tables\Columns\BadgeColumn::make('status')
                    ->getStateUsing( function ($record){
                        return $record->status ? 'Inscrit' : 'Démissionaire';
                    })
                    ->color(static function ($record): string {
                        return $record->status ? 'success' : 'danger';
                    }),
                Tables\Columns\BadgeColumn::make('state')
                    ->label('Redouble')
                    ->getStateUsing( function ($record){
                        return !$record->state ? 'Non' : 'Oui';
                    })
                    ->color(static function ($record): string {
                        return !$record->state ? 'success' : 'danger';
                    })
            ])
            ->filters([
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
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                   /*  Tables\Actions\Action::make('Editer')
                     ->fillForm(fn (Student $record): array => [
                        'classroom_id' => $record->current_classroom->id,
                        'academic_id' => $record->academic()->id,
                        'status' => $record->status,
                        'name' => $record->fname . ' ' . $record->fname,
                        'state' => $record->state,
                    ])  
                    ->form([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Noms et Prénoms')
                                    ->disabled()
                                    ->columnSpanfull(),
                                Forms\Components\Select::make('classroom_id')
                                    ->label('Classe')
                                    ->options(Classroom::query()->pluck('name', 'id'))
                                    ->required(),
                                Forms\Components\Select::make('academic_id')
                                    ->label('Année Académique')
                                    ->options(Academic::query()->pluck('name', 'id'))
                                    ->required(),
                                Forms\Components\Toggle::make('!status')
                                    ->label('Démissionaire ?'),
                                Forms\Components\Toggle::make('state')
                                    ->label('Redoublant ?'),
                            ])
                    ])
                    ->action(function (array $data, Student $record): void {

                        $policy = $record->classrooms()
                                            ->where('classroom_id', $record->current_classroom->id)
                                            ->first()
                                            ->pivot;
                      
                        $policy->classroom_id = $data['classroom_id'];
                        $policy->academic_id = $data['academic_id'];
                        $policy->status = $data['status'];
                        $policy->state = $data['state'];

                        $policy->save();
                    }) */
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TransactionsRelationManager::class,
            RelationManagers\AbsencesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassroomStudents::route('/'),
            'create' => Pages\CreateClassroomStudent::route('/create'),
            'edit' => Pages\EditClassroomStudent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $academicYear = Academic::where('status', true)->first();

        return Student::whereHas('classrooms', function($query) use ($academicYear) {
            $query->where('academic_id', $academicYear->id);
        });
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
