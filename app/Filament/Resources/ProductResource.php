<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers\TagsRelationManager;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieTagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use PhpParser\Builder;
use PHPUnit\Util\Filter;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    /**
     * @var array|string[]
     */


    protected static array $statuses = [
        'in stock' => 'in stock',
        'sold out' => 'sold out',
        'coming soon' => 'coming soon',
    ];

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
                Forms\Components\Radio::make('status')
                    ->options(self::$statuses),

                //make category preload and searchable
                Forms\Components\Select::make('category_id')
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                    ])
                    ->relationship('category', 'name'),

                //Tags
                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple(),
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
                    ->badge()
                    // Format value
                    ->money('usd')
                    ->getStateUsing(function (Product $record): float {
                        return $record->price / 100;
                    }),

                //Status
                TextColumn::make('status')
                    ->badge(),

                //Category
                TextColumn::make('category.name'),

                //Product - Tag
                TextColumn::make('tags.name')
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-o-tag'),


                /*TextColumn::make('category.name')
                ->badge()
                ->color('success'),
                TextColumn::make('tag.name')*/

            ])
            ->filters([

//                Tables\Filters\Filter::make('is_featured')
//                    ->query(fn(Builder $query): Builder => $query->where('is_featured', true)),

                SelectFilter::make('status')
                    ->options(self::$statuses)
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
            TagsRelationManager::class
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
