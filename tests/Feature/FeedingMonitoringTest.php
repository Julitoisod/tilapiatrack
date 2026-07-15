<?php

use App\Filament\Widgets\FeedingMonitoring;
use App\Models\Feed;
use App\Models\FeedingProgram;
use App\Models\FeedingSchedule;
use App\Models\Fingerling;
use App\Models\Fishpond;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function monitoringScheduleDeployedOn(string $deployedOn): FeedingSchedule
{
    $user = User::factory()->create(['role' => 'beneficiary', 'isActive' => 'active']);
    $pond = Fishpond::create(['user_id' => $user->id, 'name' => 'Pond', 'size' => 100, 'location' => 'x']);
    $fingerling = Fingerling::create([
        'fishpond_id' => $pond->id, 'species' => 'Tilapia',
        'date_deployed' => $deployedOn, 'quantity' => 300, 'weight' => 5, 'feed_amount' => 3,
    ]);
    $feed = Feed::create(['name' => 'Feed', 'price_per_kilo' => 40]);
    // 2 weeks per stage → 8 weeks (56 days) total grow-out
    $program = FeedingProgram::create([
        'feed_id' => $feed->id, 'name' => 'P',
        'fingerling_age_range' => 2, 'juvenile_age_range' => 2,
        'subadult_age_range' => 2, 'adult_age_range' => 2,
    ]);

    return FeedingSchedule::create(['fingerling_id' => $fingerling->id, 'feeding_program_id' => $program->id]);
}

function invokePrivate(object $obj, string $method, ...$args): mixed
{
    $ref = new ReflectionMethod($obj, $method);
    $ref->setAccessible(true);

    return $ref->invoke($obj, ...$args);
}

it('reports harvest-ready and 100% for a batch past its whole program', function () {
    $schedule = monitoringScheduleDeployedOn(now()->subDays(200)->toDateString());
    $widget = new FeedingMonitoring();

    expect(invokePrivate($widget, 'determineGrowthStage', $schedule))->toBe('Harvest ready')
        ->and(invokePrivate($widget, 'calculateProgress', $schedule)['progress'])->toBe(100);
});

it('reports fingerling stage and a small percentage for a fresh batch', function () {
    $schedule = monitoringScheduleDeployedOn(now()->subDays(7)->toDateString());
    $widget = new FeedingMonitoring();

    // 7 of 56 days ≈ 13%, and still in the first (fingerling) stage.
    expect(invokePrivate($widget, 'determineGrowthStage', $schedule))->toBe('Fingerling stage')
        ->and(invokePrivate($widget, 'calculateProgress', $schedule)['progress'])->toBe(13);
});

it('computes age_in_days from the deploy date', function () {
    $fingerling = Fingerling::create([
        'fishpond_id' => Fishpond::create([
            'user_id' => User::factory()->create()->id, 'name' => 'P', 'size' => 1, 'location' => 'x',
        ])->id,
        'species' => 'Tilapia', 'date_deployed' => now()->subDays(30)->toDateString(),
        'quantity' => 1, 'weight' => 1, 'feed_amount' => 1,
    ]);

    expect($fingerling->age_in_days)->toBe(30);
});
