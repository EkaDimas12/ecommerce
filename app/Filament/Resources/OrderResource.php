<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Order Info')
                ->schema([
                    Forms\Components\TextInput::make('code')
                        ->label('Order Code')
                        ->disabled(),
                    Forms\Components\TextInput::make('customer_name')
                        ->label('Customer Name')
                        ->required(),
                    Forms\Components\TextInput::make('phone')
                        ->label('Phone')
                        ->required(),
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Shipping')
                ->schema([
                    Forms\Components\Textarea::make('address')
                        ->label('Address')
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('postal_code')
                        ->label('Postal Code'),
                    Forms\Components\TextInput::make('courier')
                        ->label('Courier'),
                    Forms\Components\TextInput::make('service')
                        ->label('Service'),
                    Forms\Components\TextInput::make('tracking_number')
                        ->label('No. Resi / AWB')
                        ->placeholder('Masukkan nomor resi untuk tracking')
                        ->helperText('Isi setelah pesanan dikirim'),
                    Forms\Components\TextInput::make('shipping_cost')
                        ->label('Shipping Cost')
                        ->prefix('Rp')
                        ->numeric(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Notes')
                ->schema([
                    Forms\Components\Textarea::make('notes')
                        ->label('Catatan Internal')
                        ->placeholder('Catatan untuk admin atau kurir...')
                        ->rows(2)
                        ->columnSpanFull(),
                ])
                ->collapsible(),

            Forms\Components\Section::make('Payment & Status')
                ->schema([
                    Forms\Components\TextInput::make('subtotal')
                        ->label('Subtotal')
                        ->prefix('Rp')
                        ->numeric()
                        ->disabled(),
                    Forms\Components\TextInput::make('total')
                        ->label('Total')
                        ->prefix('Rp')
                        ->numeric()
                        ->disabled(),
                    Forms\Components\Select::make('payment_status')
                        ->label('Payment Status')
                        ->options([
                            'pending' => 'Pending',
                            'paid' => 'Paid',
                            'cod' => 'COD (Cash on Delivery)',
                            'failed' => 'Failed',
                        ])
                        ->required(),
                    Forms\Components\Select::make('order_status')
                        ->label('Order Status')
                        ->options([
                            'new' => 'New',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Order Code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->formatStateUsing(fn($state) => 'Rp' . number_format((int) $state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'cod' => 'info',
                        'failed', 'expired', 'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('order_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'delivered' => 'success',
                        'shipped' => 'info',
                        'processing' => 'warning',
                        'new' => 'gray',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'cod' => 'COD',
                        'failed' => 'Failed',
                    ]),
                Tables\Filters\SelectFilter::make('order_status')
                    ->label('Order Status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Order Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('code')->label('Order Code'),
                        Infolists\Components\TextEntry::make('customer_name')->label('Customer'),
                        Infolists\Components\TextEntry::make('phone')->label('Phone'),
                        Infolists\Components\TextEntry::make('email')->label('Email'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Shipping')
                    ->schema([
                        Infolists\Components\TextEntry::make('address')->label('Address'),
                        Infolists\Components\TextEntry::make('postal_code')->label('Postal Code'),
                        Infolists\Components\TextEntry::make('courier')->label('Courier'),
                        Infolists\Components\TextEntry::make('service')->label('Service'),
                        Infolists\Components\TextEntry::make('shipping_cost')
                            ->label('Shipping Cost')
                            ->formatStateUsing(fn($state) => 'Rp' . number_format((int) $state, 0, ',', '.')),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Payment')
                    ->schema([
                        Infolists\Components\TextEntry::make('subtotal')
                            ->formatStateUsing(fn($state) => 'Rp' . number_format((int) $state, 0, ',', '.')),
                        Infolists\Components\TextEntry::make('total')
                            ->formatStateUsing(fn($state) => 'Rp' . number_format((int) $state, 0, ',', '.')),
                        Infolists\Components\TextEntry::make('payment_status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'paid' => 'success',
                                'pending' => 'warning',
                                default => 'danger',
                            }),
                        Infolists\Components\TextEntry::make('order_status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'delivered' => 'success',
                                'shipped' => 'info',
                                'processing' => 'warning',
                                default => 'gray',
                            }),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('payment_status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
