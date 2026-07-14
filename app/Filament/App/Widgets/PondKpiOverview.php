<?php

namespace App\Filament\App\Widgets;

use App\Models\FeedConsumption;
use App\Models\Fingerling;
use App\Models\Fishpond;
use App\Models\Harvest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PondKpiOverview extends BaseWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $userId = Auth::id();

        // Total pond area (m2) owned by the current beneficiary.
        $pondArea = (float) Fishpond::where('user_id', $userId)->sum('size');

        // Total number of fingerlings stocked across all of the beneficiary's ponds.
        $stocked = (int) Fingerling::query()
            ->join('fishponds', 'fingerlings.fishpond_id', '=', 'fishponds.id')
            ->where('fishponds.user_id', $userId)
            ->sum('fingerlings.quantity');

        // Total harvested weight (kg).
        $harvestKg = (float) Harvest::query()
            ->join('fingerlings', 'harvests.fingerling_id', '=', 'fingerlings.id')
            ->join('fishponds', 'fingerlings.fishpond_id', '=', 'fishponds.id')
            ->where('fishponds.user_id', $userId)
            ->sum('harvests.total_harvest');

        // Total feed used (kg).
        $feedKg = (float) FeedConsumption::query()
            ->join('fingerlings', 'feed_consumptions.fingerling_id', '=', 'fingerlings.id')
            ->join('fishponds', 'fingerlings.fishpond_id', '=', 'fishponds.id')
            ->where('fishponds.user_id', $userId)
            ->sum('feed_consumptions.quantity');

        // Total feed cost (feed kg * price per kilo).
        $feedCost = (float) FeedConsumption::query()
            ->join('fingerlings', 'feed_consumptions.fingerling_id', '=', 'fingerlings.id')
            ->join('fishponds', 'fingerlings.fishpond_id', '=', 'fishponds.id')
            ->join('feeds', 'feed_consumptions.feed_id', '=', 'feeds.id')
            ->where('fishponds.user_id', $userId)
            ->sum(DB::raw('feed_consumptions.quantity * feeds.price_per_kilo'));

        $fcr = $harvestKg > 0 ? $feedKg / $harvestKg : null;
        $yield = $pondArea > 0 ? $harvestKg / $pondArea : null;
        $costPerKg = $harvestKg > 0 ? $feedCost / $harvestKg : null;

        return [
            Stat::make('Total Harvest', number_format($harvestKg, 2) . ' kg')
                ->description('Fish harvested to date')
                ->color('success')
                ->icon('heroicon-o-scale'),

            Stat::make('Feed Used', number_format($feedKg, 2) . ' kg')
                ->description('Total feed recorded')
                ->color('warning')
                ->icon('heroicon-o-beaker'),

            Stat::make('Feed Conversion Ratio', $fcr !== null ? number_format($fcr, 2) : 'N/A')
                ->description($fcr !== null ? 'Feed kg per kg of fish (lower is better)' : 'Needs harvest + feed data')
                ->color($fcr !== null && $fcr <= 1.8 ? 'success' : ($fcr !== null ? 'danger' : 'gray'))
                ->icon('heroicon-o-arrow-trending-down'),

            Stat::make('Yield per m2', $yield !== null ? number_format($yield, 2) . ' kg/m2' : 'N/A')
                ->description($yield !== null ? 'Harvest per pond area' : 'Needs pond area + harvest')
                ->color('info')
                ->icon('heroicon-o-square-3-stack-3d'),

            Stat::make('Feed Cost', 'PHP ' . number_format($feedCost, 2))
                ->description('Total spent on feed')
                ->color('primary')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Cost per kg Harvest', $costPerKg !== null ? 'PHP ' . number_format($costPerKg, 2) : 'N/A')
                ->description($costPerKg !== null ? 'Feed cost per kg of fish' : 'Needs harvest data')
                ->color('danger')
                ->icon('heroicon-o-calculator'),

            Stat::make('Fingerlings Stocked', number_format($stocked))
                ->description('Total stocked across ponds')
                ->color('gray')
                ->icon('heroicon-o-squares-plus'),
        ];
    }
}
