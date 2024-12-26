<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\{Academic, Student, Classroom, ClassroomStudent, Transaction};
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

class StudentResource extends Resource
{

    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static ?string $label = 'Apprénants';

    protected static ?string $navigationGroup = 'Elèves';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Information Personnel Apprenant')
                    ->schema([
                        Forms\Components\TextInput::make('fname')
                            ->label('Nom')
                            ->autofocus()
                            ->required()
                            ->maxLength(255)
                            ->autocapitalize(),
                        Forms\Components\TextInput::make('lname')
                            ->label('Prenom')
                            ->maxLength(255)
                            ->autocapitalize(),
                        Forms\Components\Radio::make('sexe')
                            ->options([
                                1 => 'Homme',
                                0 => 'Femme',
                            ])
                            ->inline()
                            ->default(1)
                            ->required(),
                        Forms\Components\DatePicker::make('born_at')
                            ->label('Date de Naissance')
                            ->required(),
                        Forms\Components\TextInput::make('born_place')
                            ->label('Lieu de naissance')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('quarter')
                            ->label('Quartier Habitation')
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\Select::make('classroom_id')
                            ->relationship('classrooms', 'name')
                            ->visibleOn('create')
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->label('Montant Inscription')
                            ->required()
                            ->visibleOn('create')
                            ->numeric(),
                        Forms\Components\Toggle::make('state')
                            ->label('Redouble ?')
                            ->default(0)
                            ->hiddenOn('edit'),
                        Forms\Components\RichEditor::make('description')
                            ->columnSpanfull()
                            ->label('Description && Allergies')
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Informations des Parents')
                    ->schema([
                        Forms\Components\TextInput::make('father_name')
                            ->label('Nom du père ou Tuteur')
                            ->maxLength(255)
                            ->autocapitalize(),
                        Forms\Components\TextInput::make('fphone')
                            ->label('Contact du Père ou Tuteur')
                            ->maxLength(255)
                            ->autocapitalize(),
                            Forms\Components\TextInput::make('mother_name')
                            ->label('Nom de la mère ou Tutrice')
                            ->maxLength(255)
                            ->autocapitalize()
                            ->required(),
                        Forms\Components\TextInput::make('mphone')
                            ->label('Contact de la mère ou Tutrice')
                            ->maxLength(255)
                            ->autocapitalize()
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                Tables\Columns\TextColumn::make('born_at')
                    ->label('Date de Naissance'),
                Tables\Columns\TextColumn::make('born_place')
                    ->label('Lieu de Naissance')
            ])
            ->filters([
                Tables\Filters\Filter::make('academic_id')
                    ->form([
                        Forms\Components\Select::make('value')
                            ->label('Année Académique')
                            ->options(Academic::all()->pluck('name', 'id'))
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'],
                                fn (Builder $query, $value): Builder => $query->whereHas('classrooms', fn (Builder $query): Builder => $query->where('academic_id', $value)),
                            );
                }),
                Tables\Filters\Filter::make('state')
                ->form([
                    Forms\Components\Toggle::make('state')
                        ->label('Redoublant')
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['state'],
                            fn (Builder $query, $value): Builder => $query->whereHas('classrooms', fn (Builder $query): Builder => $query->where('state', $value)),
                        );
                }),
                Tables\Filters\Filter::make('status')
                    ->form([
                        Forms\Components\Toggle::make('status')
                            ->label('Démissionaire')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['status'],
                                fn (Builder $query, $value): Builder => $query->whereHas('classrooms', fn (Builder $query): Builder => $query->where('status', $value)),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('Inscription')
                    ->icon('heroicon-o-plus-circle')
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
                                Forms\Components\Select::make('classroom_id')
                                    ->label('Classe')
                                    ->options(Classroom::query()->pluck('name', 'id'))
                                    ->required(),
                                Forms\Components\TextInput::make('amount')
                                    ->label('Montant')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\Radio::make('state')
                                    ->options([
                                        1 => 'Oui',
                                        0 => 'Non',
                                    ])
                                    ->label('Redouble ?')
                                    ->inline()
                                    ->default(0)
                                    ->required()
                            ])
                    ])
                    ->action(function (array $data, Student $student): void {

                        $policy = ClassroomStudent::where('student_id', $student->id)
                                                ->where('academic_id', Academic::where('status', true)->first()->id)
                                                ->first();
                        
                        if($policy) {
                            Notification::make()
                                ->danger()
                                ->title('Une Erreur est survenu')
                                ->body('Cet apprenant est deja inscrit pour l\'année scolaire en cours.')
                                ->send();
                        } else {

                            $register = $student->classrooms()->attach($data['classroom_id'], [
                                'academic_id' => Academic::where('status', true)->first()->id,
                                'state' => $data['state']
                            ]);
                        
                            Transaction::create([
                                'name' => 'Inscription',
                                'amount' => $data['amount'],
                                'classroom_student_id' => $student->classrooms()->where('academic_id' , \App\Models\Academic::where('status', true)->first()->id)->first()->pivot->id
                            ]);

                            Notification::make()
                                ->success()
                                ->title('Opération réussie')
                                ->body('Inscription a été enregistré.')
                                ->send();
                        }
                    })
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
