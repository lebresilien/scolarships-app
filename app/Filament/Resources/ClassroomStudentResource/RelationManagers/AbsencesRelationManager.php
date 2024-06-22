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
                Forms\Components\Select::make('classroom_id')
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
                Forms\Components\TextInput::make('day')
                    ->label('Nombre d\'heures')
                    ->integer()
                    ->required(),
                Forms\Components\Toggle::make('status')
                    ->label('Justifié')
                    ->reactive()
                    ->hiddenOn('create')
                    ->afterStateUpdated(fn (Toggle $component) => $component->getContainer()
                    ->getComponent('justify')
                    ->hidden(!$component->getState()) // Show if toggle is true, hide otherwise
                ),
                Forms\Components\RichEditor::make('justify')
                    ->label('Justificatif')
                    ->required()
                    ->hidden()
                    ->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('course_id')
            ->columns([
                Tables\Columns\TextColumn::make('course_id'),
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
