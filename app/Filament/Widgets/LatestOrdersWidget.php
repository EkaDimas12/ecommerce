<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Latest Orders';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Order Code')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer'),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->formatStateUsing(fn($state) => 'Rp' . number_format((int) $state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('order_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'delivered' => 'success',
                        'shipped' => 'info',
                        'processing' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y H:i'),
            ])
            ->paginated(false)
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn(Order $record): string => route('filament.admin.resources.orders.view', ['record' => $record]))
                    ->icon('heroicon-m-eye'),
            ]);
    }
}
