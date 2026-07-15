<?php

namespace App\Filament\App\Resources\ChatResource\Pages;

use App\Filament\App\Resources\ChatResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateChat extends CreateRecord
{
    protected static string $resource = ChatResource::class;

    // Drop the beneficiary straight into the new chat so they can message right away.
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
