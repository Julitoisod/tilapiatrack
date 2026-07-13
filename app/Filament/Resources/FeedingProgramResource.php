<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedingProgramResource\Pages;
use App\Models\FeedingProgram;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FeedingProgramResource extends Resource
{
    protected static ?string $model = FeedingProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Feeding Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Select::make('feed_id')
                    ->relationship(name: 'feed', titleAttribute: 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                    
                // Fingerling Stage Section
                Forms\Components\Section::make('Fingerling Stage')
                    ->schema([
                        Forms\Components\TextInput::make('fingerling_age_range')
                            ->label('Age Range (weeks)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('fingerling_feeding_frequency')
                            ->label('Feeding Frequency (times/day)')
                            ->numeric()
                            ->required(),
                        Forms\Components\Select::make('fingerling_feed_time')
                            ->label('Feed Times')
                            ->options(self::getFeedTimeOptions())
                            ->multiple()
                            ->required(),
                        Forms\Components\TextInput::make('fingerling_fish_amount')
                            ->label('Feed Amount (%)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('fingerling_protein_content')
                            ->label('Protein Content (%)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('fingerling_weight_range')
                            ->label('Weight Range (grams)')
                            ->required(),
                    ])->columns(3),

                // Juvenile Stage Section
                Forms\Components\Section::make('Juvenile Stage')
                    ->schema([
                        Forms\Components\TextInput::make('juvenile_age_range')
                            ->label('Age Range (weeks)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('juvenile_feeding_frequency')
                            ->label('Feeding Frequency (times/day)')
                            ->numeric()
                            ->required(),
                        Forms\Components\Select::make('juvenile_feed_time')
                            ->label('Feed Times')
                            ->options(self::getFeedTimeOptions())
                            ->multiple()
                            ->required(),
                        Forms\Components\TextInput::make('juvenile_fish_amount')
                            ->label('Feed Amount (%)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('juvenile_protein_content')
                            ->label('Protein Content (%)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('juvenile_weight_range')
                            ->label('Weight Range (grams)')
                            ->required(),
                    ])->columns(3),

                // Sub-Adult Stage Section
                Forms\Components\Section::make('Sub-Adult Stage')
                    ->schema([
                        Forms\Components\TextInput::make('subadult_age_range')
                            ->label('Age Range (weeks)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('subadult_feeding_frequency')
                            ->label('Feeding Frequency (times/day)')
                            ->numeric()
                            ->required(),
                        Forms\Components\Select::make('subadult_feed_time')
                            ->label('Feed Times')
                            ->options(self::getFeedTimeOptions())
                            ->multiple()
                            ->required(),
                        Forms\Components\TextInput::make('subadult_fish_amount')
                            ->label('Feed Amount (%)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('subadult_protein_content')
                            ->label('Protein Content (%)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('subadult_weight_range')
                            ->label('Weight Range (grams)')
                            ->required(),
                    ])->columns(3),

                // Adult Stage Section
                Forms\Components\Section::make('Adult Stage')
                    ->schema([
                        Forms\Components\TextInput::make('adult_age_range')
                            ->label('Age Range (weeks)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('adult_feeding_frequency')
                            ->label('Feeding Frequency (times/day)')
                            ->numeric()
                            ->required(),
                        Forms\Components\Select::make('adult_feed_time')
                            ->label('Feed Times')
                            ->options(self::getFeedTimeOptions())
                            ->multiple()
                            ->required(),
                        Forms\Components\TextInput::make('adult_fish_amount')
                            ->label('Feed Amount (%)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('adult_protein_content')
                            ->label('Protein Content (%)')
                            ->numeric()
                            ->required(),
                        Forms\Components\TextInput::make('adult_weight_range')
                            ->label('Weight Range (grams)')
                            ->required(),
                    ])->columns(3),
            ]);
    }

    private static function getFeedTimeOptions(): array
    {
        $times = [];
        $startTime = Carbon::createFromTime(0, 0);
        for ($i = 0; $i < 24; $i++) {
            $times[$startTime->format('H:i')] = $startTime->format('g:i A');
            $startTime->addHour();
        }
        return $times;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('feed.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fingerling_age_range')
                    ->label('Fingerling Age')
                    ->formatStateUsing(fn($state) => $state . ' weeks'),
                Tables\Columns\TextColumn::make('juvenile_age_range')
                    ->label('Juvenile Age')
                    ->formatStateUsing(fn($state) => $state . ' weeks'),
                Tables\Columns\TextColumn::make('subadult_age_range')
                    ->label('Sub-Adult Age')
                    ->formatStateUsing(fn($state) => $state . ' weeks'),
                Tables\Columns\TextColumn::make('adult_age_range')
                    ->label('Adult Age')
                    ->formatStateUsing(fn($state) => $state . ' weeks'),
                Tables\Columns\TextColumn::make('fingerling_protein_content')
                    ->label('F.Protein')
                    ->formatStateUsing(fn($state) => $state . '%'),
                Tables\Columns\TextColumn::make('adult_protein_content')
                    ->label('A.Protein')
                    ->formatStateUsing(fn($state) => $state . '%'),
            ])
            ->filters([])
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeedingPrograms::route('/'),
            'create' => Pages\CreateFeedingProgram::route('/create'),
            'edit' => Pages\EditFeedingProgram::route('/{record}/edit'),
        ];
    }
}