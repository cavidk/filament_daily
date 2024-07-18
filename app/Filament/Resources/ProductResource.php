<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Create a form and make fields required
                TextInput::make('name')
                    ->required(),
                     // Span 2 columns for better layout
                TextInput::make('price')
                    ->required()
                    ->columnSpan(1), // Span 1 column

                //options for select status

                Forms\Components\Select::make('status')
                    ->options([
                        'in stock' => 'in stock',
                        'sold out' => 'sold out',
                        'coming soon' => 'coming soon',
                    ])
                    ->columnSpan(1), // Span 1 column

                //making it with radio buttons

                /*Forms\Components\Radio::make('status')
                 ->options([
                     'in stock' => 'in stock',
                     'sold out' => 'sold out',
                     'coming soon' => 'coming soon',
                 ]),*/
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Make table sortable and searchable
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('price')
                    ->sortable()
                    // Format value
                    ->money('usd')
                    ->getStateUsing(function (Product $record): float {
                        return $record->price;
                    }),
                TextColumn::make('status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('price', 'desc')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
