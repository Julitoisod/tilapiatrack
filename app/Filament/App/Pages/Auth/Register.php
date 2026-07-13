<?php

namespace App\Filament\App\Pages\Auth;

use Filament\Pages\Page;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;

class Register extends BaseRegister
{
    /**
     * Force role/status server-side so a crafted request cannot self-register
     * as an admin or as an already-active account. Never trust the submitted role.
     */
    protected function handleRegistration(array $data): Model
    {
        $data['role'] = 'beneficiary';
        $data['isActive'] = 'inactive';

        return $this->getUserModel()::create($data);
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getAddressFormComponent(),
                        $this->getRoleFormComponent(), 
                    ])
                    ->statePath('data'),

            )->columns(1),
        ];
    }
 
    protected function getRoleFormComponent(): Component
    {
        return Select::make('role')
            ->options([
                'beneficiary' => 'beneficiary',
            
            ])
            ->default('beneficiary')
            ->required();
    }

    protected function getAddressFormComponent(): Component
    {
        return Textarea::make('address')
            ->label('Address')
            ->placeholder('Enter your address')
            ->rows(3)
            ->required();
    }


    
}
