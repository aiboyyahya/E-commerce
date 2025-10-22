<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OverviewStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
                ->description('Total number of products')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success')
                ->chart([2, 4, 3, 5, 7, 9, 12]) 
                ->extraAttributes([
                    'class' => 'text-center', 
                ]),

            Stat::make('Total Categories', Category::count())
                ->description('Total number of categories')
                ->descriptionIcon('heroicon-m-tag')
                ->color('info')
                ->chart([5, 3, 4, 6, 8, 6, 10])
                ->extraAttributes([
                    'class' => 'text-center',
                ]),

            Stat::make('Total Transactions', Transaction::count())
                ->description('Total number of transactions')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary')
                ->chart([1, 2, 1, 4, 6, 8, 12])
                ->extraAttributes([
                    'class' => 'text-center',
                ]),
        ];
    }
}
