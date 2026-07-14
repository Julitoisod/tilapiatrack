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

    private function determineGrowthStage($record): string
    {
        if (!$record || !$record->fingerling) {
            return 'Unknown';
        }

        $age = $record->fingerling->age_in_days ?? 0;
        
        // Define age ranges for each stage
        if ($age <= 30) {
            return 'Fingerling stage';
        } elseif ($age <= 60) {
            return 'Juvenile stage';
        } elseif ($age <= 90) {
            return 'sub adult stage';
        } else {
            return 'Adult stage';
        }
    }

   

    private function calculateProgress($record): ?array
    {
        if (!$record || !$record->fingerling) {
            return null;
        }

        $stage = $this->determineGrowthStage($record);
        $progress = match ($stage) {
            'Fingerling stage' => 33,
            'Juvenile stage' => 50,
            'sub adult stage' => 66,
            'Adult stage' => 100,
            default => 0,
        };

        return [
            'progress' => $progress,
            'color' => $progress >= 80 ? 'warning' : 'primary',
        ];
    }
}