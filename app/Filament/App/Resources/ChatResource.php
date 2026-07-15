<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ChatResource\Pages;
use App\Filament\App\Resources\ChatResource\RelationManagers;
use App\Models\Chat;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ChatResource extends Resource
{
    protected static ?string $model = Chat::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('beneficiary_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Beneficiary starts the conversation: they own the chat, and it's
                // routed to an admin. ponytail: single-admin setup, first admin wins.
                Forms\Components\Hidden::make('beneficiary_id')
                    ->default(fn() => Auth::id()),
                Forms\Components\Hidden::make('admin_id')
                    ->default(fn() => User::where('role', 'admin')->value('id')),
                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->maxLength(255)
                    // Editable when starting a chat; locked once it exists (view page).
                    ->disabled(fn($livewire) => $livewire instanceof Pages\ViewChat),
                Forms\Components\View::make('filament.beneficiary.chat-box')
                    ->visible(fn($livewire) => $livewire instanceof Pages\ViewChat)
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('admin.name')
                    ->label('Admin'),
                Tables\Columns\TextColumn::make('subject'),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListChats::route('/'),
            'create' => Pages\CreateChat::route('/create'),
            'view' => Pages\ViewChat::route('/{record}'),
        ];
    }
}
