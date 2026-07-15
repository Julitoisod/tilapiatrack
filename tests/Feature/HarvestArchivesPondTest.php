<?php

use App\Filament\App\Resources\FishpondResource;
use App\Models\Fingerling;
use App\Models\Fishpond;
use App\Models\Harvest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

function pondWithFingerling(User $user): Fingerling
{
    $pond = Fishpond::create([
        'user_id' => $user->id, 'name' => 'POND', 'size' => 500, 'location' => 'Consuelo',
    ]);

    return Fingerling::create([
        'fishpond_id' => $pond->id, 'species' => 'Tilapia',
        'date_deployed' => '2026-01-01', 'quantity' => 500, 'weight' => 5, 'feed_amount' => 3,
    ]);
}

it('archives the pond when a harvest is recorded, keeping the pond and harvest', function () {
    $user = User::factory()->create(['role' => 'beneficiary', 'isActive' => 'active']);
    $fingerling = pondWithFingerling($user);
    $pond = $fingerling->fishpond;

    $harvest = Harvest::create([
        'fingerling_id' => $fingerling->id,
        'harvest_date' => '2026-07-15',
        'total_harvest' => 480,
        'image_path' => ['harvest.jpg'],
    ]);

    // Pond is archived (flagged), but NOT deleted — and the harvest survives.
    expect($pond->fresh()->harvested_at)->not->toBeNull()
        ->and(Fishpond::find($pond->id))->not->toBeNull()
        ->and(Harvest::find($harvest->id))->not->toBeNull()
        ->and((float) $harvest->total_harvest)->toBe(480.0);
});

it('hides harvested ponds from the active Fishponds list but keeps active ones', function () {
    $user = User::factory()->create(['role' => 'beneficiary', 'isActive' => 'active']);
    Auth::login($user);

    $harvestedFingerling = pondWithFingerling($user);
    $activePond = Fishpond::create([
        'user_id' => $user->id, 'name' => 'STILL ACTIVE', 'size' => 100, 'location' => 'x',
    ]);

    Harvest::create([
        'fingerling_id' => $harvestedFingerling->id,
        'harvest_date' => '2026-07-15', 'total_harvest' => 480, 'image_path' => ['h.jpg'],
    ]);

    $listed = FishpondResource::getEloquentQuery()->pluck('name')->all();

    expect($listed)->toContain('STILL ACTIVE')
        ->and($listed)->not->toContain('POND');
});
