<?php

namespace App\Filament\Resources\ClassroomResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    public function form(Form $form): Form
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
                        Forms\Components\TextInput::make('amount')
                            ->label('Montant Inscription')
                            ->required()
                            ->visibleOn('create')
                            ->numeric(),
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('matricule')
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
                    ->options(\App\Models\Academic::all()->pluck('name', 'id'))
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['value'],
                            fn (Builder $query, $value): Builder => $query->whereHas('classrooms', fn (Builder $query): Builder => $query->where('academic_id', $value)),
                        );
                })
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
