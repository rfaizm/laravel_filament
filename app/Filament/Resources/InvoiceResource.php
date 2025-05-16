<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $pluralModelLabel = 'E-Invoice';
    protected static ?string $modelLabel = 'E-Invoice';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Nama Pelanggan') // Mengubah label
                    ->searchable(),

                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('no_invoice')
                    ->label('Nomor Invoice') // Mengubah label
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('no_invoice_update')
                    ->label('Nomor Invoice Baru') // Mengubah label
                    ->default('-')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->color(fn ($state) => $state === 'FULL PAYMENT' ? 'success' : 'warning')
                    ->formatStateUsing(fn ($state) => $state === 'FULL PAYMENT' ? 'Lunas' : 'DP')
                    ->searchable(),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('downloadPdf')
                    ->label('Invoice')
                    ->color('primary')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn (Invoice $record) => response()->streamDownload(
                        fn () => print($record->generatePdf()->output()),
                        "invoice-{$record->customer_name}-{$record->date}-{$record->no_invoice}.pdf"
                    )),
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
