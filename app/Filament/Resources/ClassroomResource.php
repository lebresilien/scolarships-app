<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassroomResource\Pages;
use App\Filament\Pages\NotePage;
use App\Filament\Resources\ClassroomResource\RelationManagers;
use App\Models\{ Academic, Classroom, Sequence };
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use App\Livewire\ViewNote;
use Livewire\Livewire;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;

class ClassroomResource extends Resource
{
    protected static ?string $model = Classroom::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()->unique(),
                Select::make('user_id')
                ->relationship('User', 'name')
                ->required(),
                Select::make('building_id')
                ->relationship('Building', 'name')
                ->required(),
                Select::make('group_id')
                ->relationship('Group', 'name')
                ->required(),
                Forms\Components\Textarea::make('description')->columnSpan(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('user.name')->searchable(),
                Tables\Columns\TextColumn::make('group.name')->searchable(),
                Tables\Columns\TextColumn::make('building.name')->searchable(),
                Tables\Columns\TextColumn::make('description')
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Notes')
                        ->icon('heroicon-o-list-bullet')
                        /*->steps([
                            Step::make('Etape 1')
                                ->description('selectionner la sequence et le cours')
                                ->schema([
                                    Select::make('sequence_id')
                                        ->label('Sequence')
                                        ->options(Sequence::query()->pluck('name', 'id'))
                                        ->required(),
                                    Select::make('course_id')
                                        ->relationship('group.courses', 'name')
                                        ->required()
                                ])
                                ->columns(2),
                            Step::make('Etape 2')
                                ->description('Remplisser les notes')
                                ->schema([
                                    Select::make('course_id')
                                        ->relationship('students', 'fname')
                                        ->required(),
                                    TextInput::make('name')->required()
                                ]),
                        ])*/
                        //->url(fn (): string => route('dashboard/notes'))
                        /* ->modalContent(function(Classroom $record) {
                          
                           $academic = Academic::where('status', true)->first();

                            $students = $record->students()
                                                ->where('status', true)
                                                ->where('academic_id', $academic->id)
                                                ->get();

                            return view('livewire.view-note', [
                                'students' => $students,
                                'sequences' => $academic->sequences
                            ]);
                        })
                        ->modalSubmitAction(false)
                        ->modalCancelAction(fn (StaticAction $action) => $action->label('Fermer')) */
                ])
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
            RelationManagers\StudentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassrooms::route('/'),
            'create' => Pages\CreateClassroom::route('/create'),
            'edit' => Pages\EditClassroom::route('/{record}/edit'),
            'notes' => Pages\NoteClassroom::route('/{record}/notes')
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
