<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Spend;
use App\Models\Income;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {

        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        // 🔹 Hitung Total Pemasukan dan Pengeluaran Saat Ini
        $pemasukan = Income::sum('total');
        $pengeluaran = Spend::sum('total');
        $saldo = $pemasukan - $pengeluaran;



        return [
            // ✅ **Stat Saldo Dompet**
            Stat::make('Saldo Dompet', 'Rp. ' . number_format($saldo, 0, ',', '.')),


            // ✅ **Stat Pemasukan**
            Stat::make('Total Pemasukan', 'Rp. ' . number_format($pemasukan, 0, ',', '.')),


            // ✅ **Stat Pengeluaran**
            Stat::make('Total Pengeluaran', 'Rp. ' . number_format($pengeluaran, 0, ',', '.')),
        ];
    }
}
