<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrimesterResource\Pages;
use App\Filament\Resources\TrimesterResource\RelationManagers;
use App\Models\Trimester;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\{ Sequence, Academic };

class TrimesterResource extends Resource
{
    protected static ?string $model = Trimester::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Notes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->autofocus()
                    ->required()
                    ->maxLength(255)
                    ->autocapitalize(),
                Forms\Components\Toggle::make('is_year')
                    ->label('Est ce le 3e trimestre?'),
                Forms\Components\Repeater::make('sequence')
                    ->schema([
                        Forms\Components\Select::make('sequence')
                        ->label('Selectionner les sequences')
                        ->options(Academic::whereStatus(true)->first()->sequences->pluck('name', 'id'))
                        ->searchable()
                    ])
                    ->defaultItems(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_year')
                    ->label('Est ce le 3e trimestre?')
                    ->boolean()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Dashbord')
                        ->url(fn ($record) => url('dashboard/trimesters/'.$record->id.'/dashboard'))
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
            'index' => Pages\ListTrimesters::route('/'),
            'create' => Pages\CreateTrimester::route('/create'),
            'edit' => Pages\EditTrimester::route('/{record}/edit'),
            'dashboard' => Pages\TrimesterDashboard::route('/{record}/dashboard')
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
