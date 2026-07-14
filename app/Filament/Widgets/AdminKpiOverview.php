<?php

namespace App\Filament\Widgets;

use App\Models\FeedConsumption;
use App\Models\Fingerling;
use App\Models\Fishpond;
use App\Models\Harvest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class AdminKpiOverview extends BaseWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $pondArea = (float) Fishpond::sum('size');
        $stocked = (int) Fingerling::sum('quantity');
        $harvestKg = (float) Harvest::sum('total_harvest');
        $feedKg = (float) FeedConsumption::sum('quantity');

        $feedCost = (float) FeedConsumption::query()
            ->join('feeds', 'feed_consumptions.feed_id', '=', 'feeds.id')
            ->sum(DB::raw('feed_consumptions.quantity * feeds.price_per_kilo'));

        $fcr = $harvestKg > 0 ? $feedKg / $harvestKg : null;
        $yield = $pondArea > 0 ? $harvestKg / $pondArea : null;
        $costPerKg = $harvestKg > 0 ? $feedCost / $harvestKg : null;

        return [
            Stat::make('Program Harvest', number_format($harvestKg, 2) . ' kg')
                ->description('All beneficiaries combined')
                ->color('success')
                ->icon('heroicon-o-scale'),

            Stat::make('Program Feed Used', number_format($feedKg, 2) . ' kg')
                ->description('Total feed distributed/recorded')
                ->color('warning')
                ->icon('heroicon-o-beaker'),

            Stat::make('Avg. Feed Conversion Ratio', $fcr !== null ? number_format($fcr, 2) : 'N/A')
                ->description($fcr !== null ? 'Feed kg per kg of fish (lower is better)' : 'Needs harvest + feed data')
                ->color($fcr !== null && $fcr <= 1.8 ? 'success' : ($fcr !== null ? 'danger' : 'gray'))
                ->icon('heroicon-o-arrow-trending-down'),

            Stat::make('Yield per m2', $yield !== null ? number_format($yield, 2) . ' kg/m2' : 'N/A')
                ->description($yield !== null ? 'Program-wide harvest per pond area' : 'Needs pond area + harvest')
                ->color('info')
                ->icon('heroicon-o-square-3-stack-3d'),

            Stat::make('Program Feed Cost', 'PHP ' . number_format($feedCost, 2))
                ->description('Total feed spend')
                ->color('primary')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Cost per kg Harvest', $costPerKg !== null ? 'PHP ' . number_format($costPerKg, 2) : 'N/A')
                ->description($costPerKg !== null ? 'Feed cost per kg of fish' : 'Needs harvest data')
                ->color('danger')
                ->icon('heroicon-o-calculator'),

            Stat::make('Fingerlings Stocked', number_format($stocked))
                ->description('Total distributed to date')
                ->color('gray')
                ->icon('heroicon-o-squares-plus'),
        ];
    }
}
