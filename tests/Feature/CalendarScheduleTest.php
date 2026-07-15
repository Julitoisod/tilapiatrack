<?php

use App\Filament\App\Pages\Calendar;
use App\Models\Feed;
use App\Models\FeedingProgram;
use App\Models\FeedingSchedule;
use App\Models\Fingerling;
use App\Models\Fishpond;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

function makeScheduleFor(User $user, string $deployedOn): void
{
    $pond = Fishpond::create([
        'user_id' => $user->id,
        'name' => 'Test Pond',
        'size' => 100,
        'location' => 'Talisay',
    ]);

    $fingerling = Fingerling::create([
        'fishpond_id' => $pond->id,
        'species' => 'Tilapia',
        'date_deployed' => $deployedOn,
        'quantity' => 300,
        'weight' => 5,
        'feed_amount' => 3,
    ]);

    $feed = Feed::create(['name' => 'Test Feed', 'price_per_kilo' => 45.00]);

    $program = FeedingProgram::create([
        'feed_id' => $feed->id,
        'name' => 'Test Program',
        'fingerling_age_range' => 2,
        'fingerling_feeding_frequency' => 2,
        'fingerling_feed_time' => ['08:00'],
        'fingerling_fish_amount' => 50,
        'fingerling_protein_content' => 30,
    ]);

    FeedingSchedule::create([
        'fingerling_id' => $fingerling->id,
        'feeding_program_id' => $program->id,
    ]);
}

it('anchors calendar events on the fingerling deploy date, not today', function () {
    $user = User::factory()->create(['role' => 'beneficiary', 'isActive' => 'active']);
    makeScheduleFor($user, '2025-01-19');

    Auth::login($user);
    $events = (new Calendar())->getEvents();

    expect($events)->not->toBeEmpty();

    $dates = collect($events)->map(fn ($e) => substr($e['start'], 0, 10))->sort()->values();

    // First feeding lands on the deploy date — the reported bug had it start today.
    expect($dates->first())->toBe('2025-01-19')
        ->and($dates->first())->not->toBe(now()->format('Y-m-d'));
});

it('gives two fingerlings with different deploy dates different calendars', function () {
    $early = User::factory()->create(['role' => 'beneficiary', 'isActive' => 'active']);
    $late = User::factory()->create(['role' => 'beneficiary', 'isActive' => 'active']);
    makeScheduleFor($early, '2025-01-19');
    makeScheduleFor($late, '2026-07-08');

    Auth::login($early);
    $earlyFirst = collect((new Calendar())->getEvents())->min('start');

    Auth::login($late);
    $lateFirst = collect((new Calendar())->getEvents())->min('start');

    // Before the fix both started "today" and were identical.
    expect(substr($earlyFirst, 0, 10))->toBe('2025-01-19')
        ->and(substr($lateFirst, 0, 10))->toBe('2026-07-08')
        ->and($earlyFirst)->not->toBe($lateFirst);
});
