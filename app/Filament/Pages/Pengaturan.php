<?php

namespace App\Filament\Pages;

use App\Models\Business;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\Store;
use UnitEnum;

class Pengaturan extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog';
    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?string $title = 'Pengaturan';

    protected string $view = 'filament.pages.pengaturan';

    public ?array $data = [];

    public function mount(): void
    {
        $store = Store::first();

        if ($store) {
            $this->form->fill($store->toArray());
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Toko')
                    ->schema([
                        TextInput::make('store_name')
                            ->label('Nama Toko')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan('full'), // full width

                        TextInput::make('address')
                            ->label('Alamat')
                            ->maxLength(255)
                            ->columnSpan('full'),

                        FileUpload::make('logo')
                            ->label('Logo')
                            ->disk('public')
                            ->image()
                            ->directory('logos')
                            ->imageEditor()
                            ->columnSpan('full'),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpan('full'),
                    ])
                    ->columns(2),

                Section::make('Sosial Media')
                    ->schema([
                        TextInput::make('instagram')->label('Instagram')->prefixIcon('heroicon-o-link')->columnSpan('full'),
                        TextInput::make('tiktok')->label('Tiktok')->prefixIcon('heroicon-o-link')->columnSpan('full'),
                        TextInput::make('whatsapp')->label('WhatsApp')->prefixIcon('heroicon-o-phone')->columnSpan('full'),
                        TextInput::make('facebook')->label('Facebook')->prefixIcon('heroicon-o-link')->columnSpan('full'),
                    ])
                    ->columns(1),

                Section::make('Marketplace')
                    ->schema([
                        TextInput::make('shopee')->label('Shopee')->columnSpan('full'),
                        TextInput::make('tokopedia')->label('Tokopedia')->columnSpan('full'),
                    ])
                    ->columns(1),
            ])
            ->columns(1)
            ->statePath('data');
    }


    public function save()
    {
        $data = $this->form->getState();

        // Simpan data ke database
        $store = Store::first();
        if (!$store) {
            $store = Store::first() ?? new Store();
        }
        $store->fill($data);
        $store->save();

        // Clear cache agar perubahan langsung terlihat
        cache()->forget('store_data');

        Notification::make()
            ->title('Pengaturan berhasil disimpan!')
            ->success()
            ->send();
    }
}
