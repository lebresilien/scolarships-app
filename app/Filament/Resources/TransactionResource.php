<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\{ ClassroomStudent, Transaction, Student, Academic };
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('classroom_student_id')
                    ->label('Apprenant')
                    ->options(function() {
                        $students = [];
                        $currentYearPolicies = ClassroomStudent::where('academic_id', Academic::whereStatus(true)->first()->id)->get();
                        foreach($currentYearPolicies as $policy) {
                            $students[$policy->id] = Student::find($policy->student_id)->full_name;
                        }
                        return $students;
                    })
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Libellé')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->label('Montant')
                    ->numeric()
                    ->required()
                    ->maxLength(255)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('value.student.full_name')
                    ->label('Noms et Prénoms'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Libellé')
                    ->searchable()
            ])
            ->filters([
                //Tables\Filters\TrashedFilter::make(),
                Tables\Filters\Filter::make('classroom_student_id')
                    ->form([
                        Forms\Components\Select::make('value')
                            ->label('Apprenant')
                            ->searchable()
                            ->options(Student::all()->pluck('full_name', 'id'))
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'],
                                function(Builder $query, $value): Builder {
                                    $policies = ClassroomStudent::where('student_id', $value)->get();
                                    return $query->whereIn('classroom_student_id', $policies->pluck('id'));
                                }
                            );
                }),
                Tables\Filters\Filter::make('academic_id')
                    ->form([
                        Forms\Components\Select::make('value')
                            ->label('Année academic')
                            ->options(Academic::all()->pluck('name', 'id'))
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'],
                                function(Builder $query, $value): Builder {
                                    $academic = Academic::find($value);
                                    return $query->whereIn('classroom_student_id', $academic->policies->pluck('id'));
                                }
                            );
                })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
