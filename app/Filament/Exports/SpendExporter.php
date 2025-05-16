<?php

namespace App\Filament\Exports;

use App\Models\Spend;
use Filament\Actions\Exports\Exporter;
use Filament\Forms\Components\TextInput;
use OpenSpout\Common\Entity\Style\Style;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;

class SpendExporter extends Exporter
{
    protected static ?string $model = Spend::class;

    public function getXlsxCellStyle(): ?Style
    {
        return (new Style())
            ->setCellAlignment(CellAlignment::CENTER)
            ->setCellVerticalAlignment(CellVerticalAlignment::CENTER)
            ->setShouldWrapText(true);
    }

    public static function getColumns(array $options = []): array
    {

        return [
            ExportColumn::make('description'),
            ExportColumn::make('source_of_spending'),
            ExportColumn::make('total'),
            ExportColumn::make('category.name_of_categories')
                ->label('Category Name'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your spend export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
