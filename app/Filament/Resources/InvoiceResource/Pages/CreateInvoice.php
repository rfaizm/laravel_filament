<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use Carbon\Carbon;
use Filament\Actions;
use App\Models\Invoice;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\Repeater;
use App\Filament\Resources\InvoiceResource;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateInvoice extends CreateRecord
{
    use HasWizard;
    protected static string $resource = InvoiceResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Identitas')
            ->description('Masukkan identitas pelanggan')
            ->schema([
                TextInput::make('customer_name')
                    ->label('Nama Pelanggan')
                    ->required(),
                DatePicker::make('date')
                    ->label('Tanggal')
                    ->required(),
                TextInput::make('no_invoice')
                    ->label('Nomor Invoice')
                    ->disabled() // Membuat field tidak bisa diedit
                    ->default($this->generateInvoiceNumber()) // Set nilai otomatis
                    ->required()
            ]),

            Step::make('List Barang')
            ->description('Masukkan list barang yang dibeli')
            ->schema([
                Repeater::make('items')
                    ->schema([
                        Grid::make(3) // Membuat grid dengan 3 kolom agar tetap sejajar
                            ->schema([
                                TextInput::make('list_item')
                                    ->label('Nama Barang')
                                    ->placeholder('Masukkan nama barang')
                                    ->required(),

                                TextInput::make('unit')
                                    ->label('Kuantitas')
                                    ->placeholder('1 Pcs, 1 Meter, dst.')
                                    ->required(),

                                TextInput::make('price')
                                    ->label('Total Harga')
                                    ->numeric()
                                    ->placeholder('1000000')
                                    ->required(),
                            ]),
                    ])
                    ->defaultItems(1) // Minimal satu item harus diisi
                    ->collapsible() // Tambahkan fitur collapse untuk UI yang lebih rapi
                    ->addActionLabel('Tambah Barang') // Ubah label tombol tambah item                    
            ]),

            Step::make('Pembayaran')
            ->description('Masukkan rincian pembayaran pelanggan')
            ->schema([
                TextInput::make('down_payment')
                    ->label('Uang Muka')
                    ->required()
                    ->numeric()
            ])
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        do {
            $data['no_invoice'] = $this->generateInvoiceNumber();
        } while (Invoice::where('no_invoice', $data['no_invoice'])->exists());

        // 🔹 Hitung total harga dari semua item
        $total_price = collect($data['items'])->sum(fn($item) => (int) $item['price']);

        // 🔹 Tentukan status berdasarkan down_payment
        $remaining_balance = $total_price - (int) $data['down_payment'];

        if ($remaining_balance > 0) {
            $data['status'] = 'DOWN PAYMENT';
        } else {
            $data['status'] = 'FULL PAYMENT';
        }

        return $data;
    }


    private function generateInvoiceNumber(): string
    {
        $today = Carbon::now();
        
        $day = $today->format('d'); // DD
        $month = $today->format('m'); // MM
        $year = $today->format('y'); // YY

        // 🔹 Hitung jumlah total invoice yang ada di database
        $totalInvoices = Invoice::count();

        // 🔹 Hitung jumlah invoice yang memiliki 'no_invoice_update' yang tidak NULL
        $updatedInvoices = Invoice::whereNotNull('no_invoice_update')->count();

        // 🔹 Nomor invoice berikutnya
        $finalCount = $totalInvoices + $updatedInvoices + 1;

        // 🔹 Format nomor urutan menjadi 3 digit (001, 002, 003, ...)
        $invoiceNumber = str_pad($finalCount, 3, '0', STR_PAD_LEFT);

        return "{$day}{$month}{$year}{$invoiceNumber}";
    }


}
