<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                            ->boolean()
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
                            ->required(),
                        Forms\Components\RichEditor::make('description')
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
                //
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
}
