<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Page;
use App\Models\FeedingProgram;
use App\Models\FeedingSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Calendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.app.pages.calendar';

    public function getEvents(): array
    {
        try {
            $feedingPrograms = FeedingSchedule::with([
                'feedingProgram.feed', 
                'fingerling.fishpond.user'
            ])
            ->whereHas('fingerling.fishpond.user', function ($query) {
                $query->where('id', Auth::id());
            })
            ->get();

            $events = [];
            $currentDate = Carbon::now()->startOfDay();

            foreach ($feedingPrograms as $program) {
                $startDate = Carbon::parse($program->start_date);
                
                // Process each growth stage
                $stages = [
                    'fingerling' => [
                        'age_range' => $program->feedingProgram->fingerling_age_range,
                        'feed_time' => $program->feedingProgram->fingerling_feed_time,
                        'protein_content' => $program->feedingProgram->fingerling_protein_content,
                        'fish_amount' => $program->feedingProgram->fingerling_fish_amount,
                        'feeding_frequency' => $program->feedingProgram->fingerling_feeding_frequency,
                        'color' => '#4CAF50' // Green
                    ],
                    'juvenile' => [
                        'age_range' => $program->feedingProgram->juvenile_age_range,
                        'feed_time' => $program->feedingProgram->juvenile_feed_time,
                        'protein_content' => $program->feedingProgram->juvenile_protein_content,
                        'fish_amount' => $program->feedingProgram->juvenile_fish_amount,
                        'feeding_frequency' => $program->feedingProgram->juvenile_feeding_frequency,
                        'color' => '#2196F3' // Blue
                    ],
                    'subadult' => [
                        'age_range' => $program->feedingProgram->subadult_age_range,
                        'feed_time' => $program->feedingProgram->subadult_feed_time,
                        'protein_content' => $program->feedingProgram->subadult_protein_content,
                        'fish_amount' => $program->feedingProgram->subadult_fish_amount,
                        'feeding_frequency' => $program->feedingProgram->subadult_feeding_frequency,
                        'color' => '#9C27B0' // Purple
                    ],
                    'adult' => [
                        'age_range' => $program->feedingProgram->adult_age_range,
                        'feed_time' => $program->feedingProgram->adult_feed_time,
                        'protein_content' => $program->feedingProgram->adult_protein_content,
                        'fish_amount' => $program->feedingProgram->adult_fish_amount,
                        'feeding_frequency' => $program->feedingProgram->adult_feeding_frequency,
                        'color' => '#F44336' // Red
                    ]
                ];

                $currentStageDate = $startDate->copy();

                foreach ($stages as $stageName => $stageData) {
                    if (!$stageData['age_range']) continue;

                    $ageRangeInWeeks = (int) $stageData['age_range'];
                    $feedTimes = is_string($stageData['feed_time']) 
                        ? json_decode($stageData['feed_time'], true) 
                        : $stageData['feed_time'];

                    if (!is_array($feedTimes)) {
                        $feedTimes = [$stageData['feed_time']];
                    }

                    // Generate events for each day in the stage
                    for ($day = 0; $day < ($ageRangeInWeeks * 7); $day++) {
                        foreach ($feedTimes as $time) {
                            if (!$time) continue;

                            try {
                                $date = $currentStageDate->copy()->addDays($day);
                                
                                // Skip if date is before today
                                if ($date->lt($currentDate)) {
                                    continue;
                                }

                                $carbonTime = Carbon::createFromFormat('H:i:s', $time);
                                $formattedTime = $carbonTime->format('h:i A');

                                $events[] = [
                                    'id' => $program->feedingProgram->id . '-' . $stageName . '-' . $day . '-' . $time,
                                    'title' => ucfirst($stageName) . ' Stage',
                                    'start' => $date->format('Y-m-d') . 'T' . $time,
                                    'backgroundColor' => $stageData['color'],
                                    'borderColor' => $stageData['color'],
                                    'description' => implode(' | ', array_filter([
                                        "Stage: " . ucfirst($stageName),
                                        "Feeding: {$stageData['feeding_frequency']}x daily",
                                        "Amount: {$stageData['fish_amount']}g",
                                        "Protein: {$stageData['protein_content']}%"
                                    ])),
                                    'extendedProps' => [
                                        'stage' => ucfirst($stageName),
                                        'feed_name' => $program->feedingProgram->feed ? $program->feedingProgram->feed->name : 'Pellets',
                                        'feeding_frequency' => $stageData['feeding_frequency'],
                                        'fish_amount' => $stageData['fish_amount'],
                                        'protein_content' => $stageData['protein_content'],
                                        'program_name' => $program->feedingProgram->name
                                    ]
                                ];
                            } catch (\Exception $timeException) {
                                Log::error('Error processing time', [
                                    'time' => $time,
                                    'error' => $timeException->getMessage()
                                ]);
                            }
                        }
                    }

                    // Move the date pointer to the start of the next stage
                    $currentStageDate->addWeeks($ageRangeInWeeks);
                }
            }

            Log::info('Total events generated', ['count' => count($events)]);
            return $events;

        } catch (\Exception $e) {
            Log::error('Error in getEvents', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }
}