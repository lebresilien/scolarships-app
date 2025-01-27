<?php

namespace App\Filament\Resources\TeacherResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Closure;
use App\Models\{ Course, School };

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->rule(static function(Forms\Get $get, Forms\Components\Component $component): Closure {
                        return static function (string $attribute, $value, Closure $fail) use ($get, $component) {
                            $existing = Course::where([
                                ['name', $value], 
                                ['teaching_unit_id', $get('teaching_unit_id')],
                                ['classroom_id', $get('classroom_id')],
                                ['teacher_id', $get('teacher_id')]
                            ])->first();

                            if ($existing && $existing->getKey() !== $component->getRecord()?->getKey()) {
                                $group = ucwords($get('teaching_unit_id'));
                                $fail("The {$value} Teaching Unit \"${group}\" already exists.");
                            }
                    };
                }),
                Forms\Components\TextInput::make('coefficient')
                    ->hidden(fn (Forms\Get $get) => School::all()->first()->is_primary_school)
                    ->required(),
                Forms\Components\Select::make('teaching_unit_id')
                    ->label('Unité d\'enseignement')
                    ->relationship('teachingUnit', 'name')
                    ->required(),
                Forms\Components\Select::make('classroom_id')
                    ->label('Salle de classe')
                    ->relationship('classroom', 'name')
                    ->hidden(fn (Forms\Get $get) => School::all()->first()->is_primary_school)
                    ->required(),
                Forms\Components\Textarea::make('description')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Libelle')
                ->searchable(),
                Tables\Columns\TextColumn::make('coefficient'),
                Tables\Columns\TextColumn::make('teachingUnit.name')
                    ->label('Unité d\'enseignement')
                    ->visible(!School::all()->first()->is_primary_school)
                    ->searchable(),
                Tables\Columns\TextColumn::make('classroom.name')
                    ->label('Classe')
                    ->visible(!School::all()->first()->is_primary_school)
                    ->searchable(),
                Tables\Columns\TextColumn::make('teachingUnit.group.name')
                    ->label('Groupe')
                    //->visible(!School::all()->first()->is_primary_school)
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
