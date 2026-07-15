<?php

namespace App\Filament\Widgets;

use App\Models\FeedingProgram;
use App\Models\FeedingSchedule;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class FeedingMonitoring extends BaseWidget
{
    protected static bool $isLazy = false;
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                FeedingSchedule::query()
                    ->with(['feedingProgram', 'fingerling', 'fingerling.fishpond.user'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('fingerling.fishpond.name')
                    ->label('Name')
                    ->formatStateUsing(function ($record) {
                        $stage = $this->determineGrowthStage($record);
                        return view('components.name-with-stage', [
                            'name' => $record->fingerling->fishpond->name,
                            'stage' => $stage
                        ]);
                    }),
                Tables\Columns\TextColumn::make('feedingProgram.feed.name')
                    ->label('Feeding Type'),
                Tables\Columns\ViewColumn::make('progress')
                    ->label('Progress')
                    ->view('filament.tables.columns.progress-bar')
                    ->getStateUsing(function ($record) {
                        return $this->calculateProgress($record);
                    }),
            ]);
    }

    /**
     * Cumulative day boundaries for each stage, taken from the schedule's
     * feeding program (weeks → days). Falls back to sane defaults if unset.
     */
    private function stageBoundaries($record): array
    {
        $p = $record->feedingProgram;
        $weeks = fn ($v, $default) => ((int) ($v ?? 0)) ?: $default;

        $f = $weeks($p?->fingerling_age_range, 4) * 7;
        $j = $f + $weeks($p?->juvenile_age_range, 4) * 7;
        $s = $j + $weeks($p?->subadult_age_range, 4) * 7;
        $a = $s + $weeks($p?->adult_age_range, 4) * 7;

        return ['fingerling' => $f, 'juvenile' => $j, 'subadult' => $s, 'total' => $a];
    }

    private function determineGrowthStage($record): string
    {
        if (!$record || !$record->fingerling) {
            return 'Unknown';
        }

        $age = $record->fingerling->age_in_days;
        $b = $this->stageBoundaries($record);

        if ($age < $b['fingerling']) {
            return 'Fingerling stage';
        } elseif ($age < $b['juvenile']) {
            return 'Juvenile stage';
        } elseif ($age < $b['subadult']) {
            return 'Sub-adult stage';
        } elseif ($age < $b['total']) {
            return 'Adult stage';
        }

        return 'Harvest ready';
    }

    private function calculateProgress($record): ?array
    {
        if (!$record || !$record->fingerling) {
            return null;
        }

        $age = $record->fingerling->age_in_days;
        $total = $this->stageBoundaries($record)['total'];

        // Real elapsed progress through the whole grow-out, not a per-stage constant.
        $progress = $total > 0 ? (int) min(100, round($age / $total * 100)) : 0;

        return [
            'progress' => $progress,
            'color' => $progress >= 80 ? 'warning' : 'primary',
        ];
    }
}