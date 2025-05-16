<?php

namespace App\Filament\Resources\SpendResource\Pages;

use App\Filament\Resources\SpendResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpends extends ListRecords
{
    protected static string $resource = SpendResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Pengeluaran'),
        ];
    }
}
