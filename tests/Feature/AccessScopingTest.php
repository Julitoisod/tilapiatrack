<?php

use App\Filament\App\Resources\FishpondResource;
use App\Http\Middleware\VerifyAdmin;
use App\Models\Fishpond;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

uses(RefreshDatabase::class);

function makeBeneficiary(string $active = 'active'): User
{
    return User::factory()->create([
        'role' => 'beneficiary',
        'isActive' => $active,
    ]);
}

function makeAdmin(): User
{
    return User::factory()->create([
        'role' => 'admin',
        'isActive' => 'active',
    ]);
}

function makePond(User $user): Fishpond
{
    return Fishpond::create([
        'user_id' => $user->id,
        'name' => 'Pond ' . fake()->word(),
        'size' => 100,
        'location' => 'Poblacion',
    ]);
}

it('scopes fishpond queries to the authenticated beneficiary', function () {
    $me = makeBeneficiary();
    $other = makeBeneficiary();

    makePond($me);
    makePond($me);
    makePond($other);

    $this->actingAs($me);

    $ownerIds = FishpondResource::getEloquentQuery()->pluck('user_id')->unique()->all();

    expect(FishpondResource::getEloquentQuery()->count())->toBe(2)
        ->and($ownerIds)->toBe([$me->id]);
});

it('enforces panel access via canAccessPanel', function () {
    $admin = makeAdmin();
    $beneficiary = makeBeneficiary();
    $inactive = makeBeneficiary('inactive');

    $adminPanel = Filament::getPanel('admin');
    $appPanel = Filament::getPanel('app');

    expect($admin->canAccessPanel($adminPanel))->toBeTrue()
        ->and($admin->canAccessPanel($appPanel))->toBeFalse()
        ->and($beneficiary->canAccessPanel($appPanel))->toBeTrue()
        ->and($beneficiary->canAccessPanel($adminPanel))->toBeFalse()
        ->and($inactive->canAccessPanel($appPanel))->toBeFalse();
});

it('blocks non-admins through the VerifyAdmin middleware', function () {
    $this->actingAs(makeBeneficiary());

    $middleware = new VerifyAdmin();

    expect(fn () => $middleware->handle(Request::create('/admin'), fn ($r) => response('ok')))
        ->toThrow(HttpException::class);
});

it('allows admins through the VerifyAdmin middleware', function () {
    $this->actingAs(makeAdmin());

    $middleware = new VerifyAdmin();
    $response = $middleware->handle(Request::create('/admin'), fn ($r) => response('ok'));

    expect($response->getContent())->toBe('ok');
});
