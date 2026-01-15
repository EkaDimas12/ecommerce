<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $pendingOrders = Order::where('payment_status', 'pending')->count();
        $totalProducts = Product::count();
        $totalUsers = User::count();

        return [
            Stat::make('Total Orders', $totalOrders)
                ->description('All orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, $totalOrders]),

            Stat::make('Total Revenue', 'Rp' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Paid orders only')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pending Orders', $pendingOrders)
                ->description('Awaiting payment')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingOrders > 0 ? 'warning' : 'gray'),

            Stat::make('Total Products', $totalProducts)
                ->description('Active products')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),

            Stat::make('Total Users', $totalUsers)
                ->description('Registered customers')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray'),
        ];
    }
}
