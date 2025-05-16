<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade\Pdf;

class Invoice extends Model
{
    protected $table = 'invoice';

    protected $fillable = [
        'customer_name',
        'date',
        'no_invoice',
        'no_invoice_update',
        'items',
        'down_payment',
        'status',
    ];

    protected $casts = [
        'items' => 'array', // Mengubah JSON menjadi array saat diambil dari database
    ];

    
    public function generatePdf()
    {
        // $this sudah merupakan objek Invoice yang valid
        $invoice = $this;
    
        // Format tanggal menggunakan Carbon atau PHP date()
        $formattedDate = date('d/m/Y', strtotime($invoice['date']));
    
        // Mengambil data items dan menghitung subtotal
        $items = $invoice['items']; // Mengubah JSON string menjadi array
    
        // Inisialisasi $subtotal dan $amount
        $subtotal = 0;
        $amount = 0;
    
        // Menghitung subtotal dan AMOUNT kumulatif
        foreach ($items as $index => $item) {
            // Format price menjadi format dengan pemisah ribuan
            $priceFormatted = number_format((float) $item['price'], 0, ',', '.');
    
            // Menjumlahkan harga item (price) untuk subtotal
            $subtotal += (float) $item['price'];
    
            // Hitung AMOUNT kumulatif
            $amount += (float) $item['price']; // Menjumlahkan harga item untuk AMOUNT kumulatif
            $items[$index]['amount'] = number_format($amount, 0, ',', '.'); // Menyimpan AMOUNT kumulatif pada setiap item
            $items[$index]['price'] = $priceFormatted; // Menyimpan price yang telah diformat
        }
    
        // Menghitung Total Due (Total yang harus dibayar setelah down payment)
        $totalDue = $subtotal - (float) $invoice['down_payment'];
        $down_payment = number_format((float) $invoice['down_payment'], 0, ',', '.'); // Format down_payment dengan pemisah ribuan  
        $totalDueFormatted = number_format($totalDue, 0, ',', '.'); // Format totalDue dengan pemisah ribuan
    
        // Muat view dengan data invoice, tanggal yang sudah diformat, subtotal, items, totalDue, dan amount
        $pdf = PDF::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'formattedDate' => $formattedDate,
            'subtotal' => number_format($subtotal, 0, ',', '.'), // Format subtotal dengan pemisah ribuan
            'items' => $items, // Kirimkan items dengan kolom AMOUNT yang sudah dihitung dan diformat
            'totalDue' => $totalDueFormatted, // Kirimkan nilai totalDue yang sudah diformat
            'down_payment' => $down_payment, // Kirimkan nilai down_payment yang sudah diformat
        ]);
    
        // Mengembalikan file PDF
        return $pdf;
    }
    
    


    
}
