<x-filament-panels::page>
    <div class="p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-xl font-semibold">Detail Pengeluaran</h2>

        <div class="mt-4">
            <p><strong>Jenis Pengeluaran:</strong> {{ $record->source_of_spending }}</p>
            <p><strong>Total:</strong> Rp {{ number_format($record->total, 0, ',', '.') }}</p>
            <p><strong>Kategori:</strong> {{ $record->category->name_of_categories }}</p>
            <p><strong>Deskripsi:</strong> {{ $record->description }}</p>
            <p><strong>Dibuat Pada:</strong> {{ $record->created_at->format('d-m-Y H:i') }}</p>
        </div>
    </div>
</x-filament-panels::page>
