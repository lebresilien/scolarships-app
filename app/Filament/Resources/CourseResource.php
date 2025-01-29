<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Course;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Closure;
use App\Models\{ Teacher, School, Classroom };

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static ?string $label = 'Cours';

    protected static ?string $navigationGroup = 'Enseignements';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Libéllé')
                    ->autofocus()
                    ->required()
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
                                $fail("The {$value} Course \"${group}\" already exists.");
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
                Forms\Components\Select::make('teacher_id')
                    ->label('Enseignant')
                    ->relationship('teacher', 'name')
                    ->hidden(fn (Forms\Get $get) => School::all()->first()->is_primary_school)
                    ->required(),
                Forms\Components\Select::make('classroom_id')
                    ->label('Salle de classe')
                    ->relationship('classroom', 'name')
                    ->hidden(fn (Forms\Get $get) => School::all()->first()->is_primary_school)
                    ->required(),
                Forms\Components\Textarea::make('description')
                //->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('coefficient'),
                Tables\Columns\TextColumn::make('teachingUnit.name')
                    ->label('Unité d\'enseignement')
                    ->visible(!School::all()->first()->is_primary_school)
                    ->searchable(),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Enseignant')
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
                //Tables\Filters\TrashedFilter::make(),
                Tables\Filters\Filter::make('teacher_id')
                    ->form([
                        Forms\Components\Select::make('value')
                            ->label('Enseignant')
                            ->options(Teacher::all()->pluck('name', 'id'))
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'],
                                fn (Builder $query, $value): Builder => $query->where('teacher_id', $value),
                            );
                }),
                Tables\Filters\Filter::make('classroom_id')
                    ->form([
                        Forms\Components\Select::make('value')
                            ->label('Salle de classe')
                            ->options(Classroom::all()->pluck('name', 'id'))
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['value'],
                                fn (Builder $query, $value): Builder => $query->where('classroom_id', $value),
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
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
