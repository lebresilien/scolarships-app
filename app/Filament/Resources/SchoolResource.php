<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolResource\Pages;
use App\Filament\Resources\SchoolResource\RelationManagers;
use App\Models\School;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->unique()
                    ->autofocus()
                    ->required()
                    ->maxLength(255)
                    ->autocapitalize(),

                Forms\Components\FileUpload::make('logo')
                    ->label('Logo')
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->image()
                    ->required(),

                Forms\Components\TextInput::make('phone_number')
                    ->label('Téléphone')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\TextInput::make('postal_address')
                    ->label('Boite Postale')
                    ->maxLength(255)
                    ->required()
                    ->autocapitalize(),

                Forms\Components\Textarea::make('registration_number')
                    ->label('Numéro d\'autorisation')
                    ->maxLength(255)
                    ->required(),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_primary_school')
                    ->label('Ecole Primaire')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom'),
                Tables\Columns\TextColumn::make('postal_address')
                    ->label('Adresse Postale'),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Téléphone'),
                Tables\Columns\TextColumn::make('registration_number')
                ->label('Numéro d\'immatriculation'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListSchools::route('/'),
            'create' => Pages\CreateSchool::route('/create'),
            'edit' => Pages\EditSchool::route('/{record}/edit'),
        ];
    }
}
