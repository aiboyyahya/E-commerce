<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('product_name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('purchase_price')
                    ->required()
                    ->numeric(),
                TextInput::make('selling_price')
                    ->required()
                    ->numeric(),
                TextInput::make('stock')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['active' => 'Active', 'inactive' => 'Inactive'])
                    ->default('active')
                    ->required(),
                FileUpload::make('image')
                    ->image()
                    ->required(),
            ]);
    }
}
