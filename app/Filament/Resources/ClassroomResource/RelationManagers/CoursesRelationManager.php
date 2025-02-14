<?php

namespace App\Filament\Resources\ClassroomResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\{ Teacher, School, Classroom, TeachingUnit };

class CoursesRelationManager extends RelationManager
{
    protected static string $relationship = 'courses';
   
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Libéllé')
                    ->required()
                    ->maxLength(255)
                    ->rule(static function(Forms\Get $get, Forms\Components\Component $component): Closure {
                        return static function (string $attribute, $value, Closure $fail) use ($get, $component) {
                            $existing = Course::where([
                                ['name', $value], 
                                ['teaching_unit_id', $get('teaching_unit_id')],
                                ['classroom_id', $get('classroom_id')],
                                //['teacher_id', $get('teacher_id')]
                            ])->first();

                            if ($existing && $existing->getKey() !== $component->getRecord()?->getKey()) {
                                $group = ucwords($get('teaching_unit_id'));
                                $fail("The {$value} Course \"${group}\" already exists.");
                            }
                        };
                    }),
                    Forms\Components\TextInput::make('coefficient')
                        ->hidden(fn (Forms\Get $get) => School::all()->first()->is_primary_school)
                        ->required(),
                    Forms\Components\Select::make('teaching_unit_id')
                        ->relationship('teachingUnit', 'name')
                        ->label('Unité d\'enseignement')
                        ->options(fn () => TeachingUnit::where('group_id', $this->getOwnerRecord()->group->id)->get()->pluck('name', 'id')->all())
                        ->default('teaching_unit_id')
                        ->required(),
                    Forms\Components\Select::make('teacher_id')
                        ->label('Enseignant')
                        ->relationship('teacher', 'name')
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
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('coefficient'),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Enseignant')
                    ->visible(!\App\Models\School::all()->first()->is_primary_school)
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
