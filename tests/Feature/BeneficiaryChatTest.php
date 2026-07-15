<?php

use App\Filament\App\Resources\ChatResource;
use App\Filament\App\Resources\ChatResource\Pages\CreateChat;
use App\Models\Chat;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('registers the create page so beneficiaries have a route to start a chat', function () {
    expect(array_keys(ChatResource::getPages()))->toContain('create');
});

it('lets a beneficiary start a chat that is routed to an admin', function () {
    $admin = User::factory()->create(['role' => 'admin', 'isActive' => 'active']);
    $beneficiary = User::factory()->create(['role' => 'beneficiary', 'isActive' => 'active']);

    Filament::setCurrentPanel(Filament::getPanel('app'));
    $this->actingAs($beneficiary);

    Livewire::test(CreateChat::class)
        ->fillForm(['subject' => 'My tilapia are not eating'])
        ->call('create')
        ->assertHasNoFormErrors();

    $chat = Chat::first();

    expect($chat)->not->toBeNull()
        ->and($chat->beneficiary_id)->toBe($beneficiary->id)
        ->and($chat->admin_id)->toBe($admin->id)
        ->and($chat->subject)->toBe('My tilapia are not eating')
        ->and($chat->status)->toBe('active');
});
