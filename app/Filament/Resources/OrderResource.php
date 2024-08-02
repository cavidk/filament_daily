<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('name')
                    ->label('Product Name')
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->label('Price $')
                    ->columnSpan(1)


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->badge(),

                Tables\Columns\TextColumn::make('product.name')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('user.name')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('price')
                    ->money('usd')
                    ->getStateUsing(function (Order $record): float {
                        return $record->price / 100;
                    })
                    ->badge()
                    ->color('info')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()
                        ->formatStateUsing(fn($state) => '$' . number_format($state / 100, 2))
                    ),
            ])
            ->defaultSort('created_at', 'desc')
//            ->defaultGroup('product.name')

            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
