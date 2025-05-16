<?php

namespace App\Filament\Widgets;

use App\Models\Income;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class WidgetIncomeChart extends ChartWidget
{
    protected static ?string $heading = 'Pemasukan';

    protected static string $color = 'success';

    protected function getData(): array
    {
        $data = Trend::model(Income::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('total'); // Menggunakan sum() pada kolom "total"

    
        return [
            'datasets' => [
                [
                    'label' => 'Pemasukkan',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->translatedFormat('F')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
