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
use Illuminate\Database\Eloquent\SoftDeletingScope;;

class ClassroomStudentResource extends Resource
{

    protected static ?string $model = ClassroomStudent::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
                Tables\Columns\TextColumn::make('current_classroom')
                    ->label('Classe'),
                Tables\Columns\TextColumn::make('current_amount')
                    ->label('Montant'),
                Tables\Columns\BadgeColumn::make('status')
                    ->getStateUsing( function ($record){
                        return $record->status ? 'Inscrit' : 'Démissionaire';
                    })
                    ->color(static function ($record): string {
                        return $record->status ? 'success' : 'danger';
                    })
            ])
            ->filters([
                Tables\Filters\Filter::make('classroom_id')
                    ->form([
                        Forms\Components\Select::make('value')
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListClassroomStudents::route('/'),
            'create' => Pages\CreateClassroomStudent::route('/create'),
            'edit' => Pages\EditClassroomStudent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $academicYear = Academic::where('status', true)->first();;

        return Student::whereHas('classrooms', function($query) use ($academicYear) {
            $query->where('academic_id', $academicYear->id);
        });
    }
}
