<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\SelectColumn;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_code')
                    ->searchable(),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable(),
                TextColumn::make('address')
                ->label('Alamat')
                    ->limit(50),
                   TextColumn::make('status')
                    ->badge(),
                TextColumn::make('total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('payment_method')
                ->label('Metode Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'midtrans' => 'primary',
                    }),
                TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'settlement' => 'success',
                        'cancel' => 'danger',
                        'expire' => 'gray',
                        'deny' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
