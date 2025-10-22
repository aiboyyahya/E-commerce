<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\User;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_code')
                    ->disabled()
                    ->required(),
                Select::make('customer_id')
                    ->disabled()
                    ->label('Customer')
                    ->options(User::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Textarea::make('address')
                    ->disabled()
                    ->label('Alamat')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'packing' => 'Packing',
                        'sent' => 'Sent',
                        'done' => 'Done',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
                    ->required(),
                TextInput::make('total')
                    ->disabled()
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0.0),
                Textarea::make('notes')
                    ->disabled()
                    ->label('Catatan')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('payment_method')
                    ->disabled()
                    ->label('Metode Pembayaran')
                    ->options([
                        'midtrans' => 'Midtrans',
                    ])
                    ->default('midtrans')
                    ->required(),
            ]);
    }
}
