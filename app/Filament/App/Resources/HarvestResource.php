<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\HarvestResource\Pages;
use App\Filament\App\Resources\HarvestResource\RelationManagers;
use App\Models\Harvest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class HarvestResource extends Resource
{
    protected static ?string $model = Harvest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Harvest Management';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('fingerling_id')
                    ->options(function () {
                        return \App\Models\Fingerling::whereHas('fishpond.user', function ($query) {
                            $query->where('id', Auth::user()->id);
                        })
                            ->with('fishpond.user')
                            ->get()
                            ->mapWithKeys(function ($fingerling) {
                                return [
                                    $fingerling->id => $fingerling->fishpond->name . ' - ' . $fingerling->species . ' | ' . $fingerling->quantity,
                                ];
                            });
                    }),
                Forms\Components\DatePicker::make('harvest_date')
                    ->required(),
                Forms\Components\TextInput::make('total_harvest')
                    ->placeholder('Kilograms')
                    ->required()
                    ->numeric(),
                Forms\Components\FileUpload::make('image_path')
                    ->label('Harvest Picture')
                    ->required()
                    ->imageResizeMode('cover')
                    ->openable()
                    ->multiple()
                    ->downloadable()
                    ->panelLayout('grid')
                    ->columnSpanFull(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fingerling.fishpond.name')
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_harvest')
                    ->formatStateUsing(fn($state) => $state . ' kg')
                    ->searchable(),
                Tables\Columns\TextColumn::make('harvest_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Fishpond Pictures')
                    ->stacked()
                    ->circular()
                    ->limit(3)
                    ->limitedRemainingText(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListHarvests::route('/'),
            'create' => Pages\CreateHarvest::route('/create'),
            'edit' => Pages\EditHarvest::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
        ->whereHas('fingerling.fishpond', function (Builder $query) {
            $query->where('user_id', Auth::id());
        });
    }
}
