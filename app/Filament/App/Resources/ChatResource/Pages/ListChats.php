<?php

namespace App\Filament\App\Resources\ChatResource\Pages;

use App\Filament\App\Resources\ChatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChats extends ListRecords
{
    protected static string $resource = ChatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
