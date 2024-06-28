<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SequenceResource\Pages;
use App\Filament\Resources\SequenceResource\RelationManagers;
use App\Models\Sequence;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SequenceResource extends Resource
{
    protected static ?string $model = Sequence::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(ignorable: fn ($record) => $record),
                Forms\Components\Select::make('academic_id')
                    ->relationship('Academic', 'name')
                    ->required(),
                Forms\Components\Toggle::make('status')
                    ->hiddenOn('create'),
                Forms\Components\Textarea::make('description')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('academic.name')
                    ->label('Année académique')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('description')
                    ->toggleable()
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('academic')
                    ->label('Année académique')
                    ->relationship('academic', 'name')
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Dashbord')
                        ->url(fn ($record) => url('dashboard/sequences/'.$record->id.'/dashboard'))
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSequences::route('/'),
            'create' => Pages\CreateSequence::route('/create'),
            'edit' => Pages\EditSequence::route('/{record}/edit'),
            'dashboard' => Pages\SequenceDashboard::route('/{record}/dashboard')
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
