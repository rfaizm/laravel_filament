<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Spend;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class WidgetSpendingChart extends ChartWidget
{
    protected static ?string $heading = 'Pengeluaran';

    protected static string $color = 'danger';

    protected function getData(): array
    {
        $data = Trend::model(Spend::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('total'); // Menggunakan sum() pada kolom "total"

        return [
            'datasets' => [
                [
                    'label' => 'Pengeluaran',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            // 🔹 Ubah tanggal menjadi nama bulan dalam bahasa Indonesia
            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->translatedFormat('F')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

