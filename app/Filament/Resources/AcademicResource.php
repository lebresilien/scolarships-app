<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AcademicResource\Pages;
use App\Filament\Resources\AcademicResource\RelationManagers;
use App\Models\Academic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;

class AcademicResource extends Resource
{
    protected static ?string $model = Academic::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull()
                    ->unique(ignorable: fn ($record) => $record),
                Forms\Components\Toggle::make('status')
                ->label('année en cours')
                ->hiddenOn('create'),
                Forms\Components\RichEditor::make('description')
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Nom')
                ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Anneé en cours')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('description')
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //Tables\Filters\TrashedFilter::make(),
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
            RelationManagers\SequencesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAcademics::route('/'),
            'create' => Pages\CreateAcademic::route('/create'),
            'edit' => Pages\EditAcademic::route('/{record}/edit'),
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
