<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use Carbon\Carbon;
use Filament\Actions;
use App\Models\Invoice;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\InvoiceResource;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('customer_name')
                ->label('Nama Pelanggan')
                ->required(),

            Select::make('status')
                ->label('Status Pembayaran')
                ->options([
                    'FULL PAYMENT' => 'Lunas',
                    'DOWN PAYMENT' => 'DP',
                ])
                ->required(),
        ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ambil record sebelum diupdate
        $invoice = Invoice::find($this->record->id);

        // 🔹 Cek apakah status berubah dari DOWN PAYMENT ke FULL PAYMENT
        if ($invoice->status === 'DOWN PAYMENT' && $data['status'] === 'FULL PAYMENT') {
            
            // 🔹 Cek apakah sudah ada no_invoice_update
            if (!is_null($invoice->no_invoice_update)) {
                // ❌ Tampilkan alert & cegah update
                Notification::make()
                    ->title('Peringatan')
                    ->danger()
                    ->body('Invoice ini sudah pernah diperbarui ke FULL PAYMENT. Perubahan tidak diizinkan.')
                    ->send();

                throw new \Exception('Invoice ini sudah memiliki nomor update dan tidak dapat diperbarui lagi.');
            }

            // 🔹 Jika belum ada no_invoice_update, generate nomor baru
            $data['no_invoice_update'] = $this->generateUpdatedInvoiceNumber();
        }

        return $data;
    }

    private function generateUpdatedInvoiceNumber(): string
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
