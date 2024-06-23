<?php

namespace App\Filament\Resources\ClassroomStudentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\{ Sequence, Course };
use Illuminate\Support\Facades\Log;

class AbsencesRelationManager extends RelationManager
{
    protected static string $relationship = 'absences';
    protected static ?string $model = Student::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('course_id')
                    ->options(function (RelationManager $livewire) {
                        return Course::whereIn('teaching_unit_id', $livewire->getOwnerRecord()->current_classroom->group->teachings->pluck('id'))->get()->pluck('name', 'id');
                    })
                    ->required(),
                Forms\Components\Select::make('sequence_id')
                        ->label('Sequence')
                        ->options(Sequence::query()->pluck('name', 'id'))
                        ->required(),
                Forms\Components\DateTimePicker::make('day')
                    ->label('Date')
                    ->seconds(false) 
                    ->required(),
                Forms\Components\TextInput::make('hour')
                    ->label('Nombre d\'heures')
                    ->integer()
                    ->required(),
                Forms\Components\Toggle::make('status')
                    ->label('Justifié')
                    ->live()
                    ->hiddenOn('create'),
                Forms\Components\TextInput::make('justify_hour')
                    ->label('Nombre d\'heures justifiées')
                    ->integer()
                    ->required(fn ($get): bool => filled($get('status')))
                    ->visible(fn ($get): bool =>  $get('status') === true)
                    ->lte('hour')
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('justify')
                    ->label('Justificatif')
                    ->required(fn ($get): bool => filled($get('status')))
                    ->visible(fn ($get): bool =>  $get('status') === true)
                    ->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('course_id')
            ->columns([
                Tables\Columns\TextColumn::make('course.name')
                    ->label('Cours'),
                Tables\Columns\TextColumn::make('sequence.name')
                    ->label('Sequence'),
                Tables\Columns\TextColumn::make('day')
                    ->label('Date et Heure'),
                Tables\Columns\TextColumn::make('hour')
                    ->label('Nombre d\'heures'),
                Tables\Columns\TextColumn::make('justify_hour')
                    ->label('Nombre d\'heures justifiées'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
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
