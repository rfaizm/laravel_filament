<?php

namespace App\Filament\Exports;

use App\Models\Income;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Style;

class IncomeExporter extends Exporter
{
    protected static ?string $model = Income::class;

    public function getXlsxCellStyle(): ?Style
    {
        return (new Style())
            ->setCellAlignment(CellAlignment::CENTER)
            ->setCellVerticalAlignment(CellVerticalAlignment::CENTER)
            ->setShouldWrapText(true);
    }

    public static function getColumns(): array
    {
    
        return [
            ExportColumn::make('description'),
            ExportColumn::make('source_of_income'),
            ExportColumn::make('total'),
            ExportColumn::make('no_invoice'),
            ExportColumn::make('category.name_of_categories')
                ->label('Category Name'), // Label untuk kolom ini
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your income export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
