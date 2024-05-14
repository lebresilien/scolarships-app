<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeachingUnitResource\Pages;
use App\Filament\Resources\TeachingUnitResource\RelationManagers;
use App\Models\TeachingUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Closure;

class TeachingUnitResource extends Resource
{
    protected static ?string $model = TeachingUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->autofocus()
                    ->required()
                    ->rule(static function(Forms\Get $get, Forms\Components\Component $component): Closure {
                        return static function (string $attribute, $value, Closure $fail) use ($get, $component) {
                            $existing = TeachingUnit::where([
                                ['name', $value], 
                                ['group_id', $get('group_id')]
                            ])->first();

                            if ($existing && $existing->getKey() !== $component->getRecord()?->getKey()) {
                                $group = ucwords($get('group_id'));
                                $fail("The {$group} Teaching Unit \"${value}\" already exists.");
                            }
                        };
                    }),
                Forms\Components\Select::make('group_id')
                    ->relationship('group', 'name')
                    ->required(),
                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Libellé')
                    ->searchable(),
                Tables\Columns\TextColumn::make('group.name'),
                Tables\Columns\TextColumn::make('description')
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
            'index' => Pages\ListTeachingUnits::route('/'),
            'create' => Pages\CreateTeachingUnit::route('/create'),
            'edit' => Pages\EditTeachingUnit::route('/{record}/edit'),
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
