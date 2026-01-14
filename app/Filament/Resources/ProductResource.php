<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Catalog';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('category_id')
                ->label('Category')
                ->options(Category::query()->orderBy('name')->pluck('name', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('name')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('slug', Str::slug($state));
                }),

            Forms\Components\TextInput::make('slug')
                ->required()
                ->helperText('Slug untuk URL produk.'),

            Forms\Components\TextInput::make('price')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            Forms\Components\Toggle::make('is_best_seller')
                ->label('Best Seller')
                ->default(false),

            Forms\Components\Textarea::make('description')
                ->rows(6)
                ->required(),

            Forms\Components\FileUpload::make('main_image')
                ->label('Main Image')
                ->image()
                ->disk('public')
                ->directory('products')
                ->imageEditor() // opsional editor crop
                ->helperText('Upload foto produk utama.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('main_image')->label('Image')->square(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('Category')->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(fn ($state) => 'Rp' . number_format((int)$state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_best_seller')
                    ->boolean()
                    ->label('Best Seller'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(Category::query()->orderBy('name')->pluck('name', 'id')),
                Tables\Filters\TernaryFilter::make('is_best_seller')->label('Best Seller'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
