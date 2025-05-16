<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Income;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use App\Filament\Exports\IncomeExporter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Resources\IncomeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\IncomeResource\RelationManagers;

class IncomeResource extends Resource
{
    protected static ?string $model = Income::class;

    protected static ?string $navigationIcon = 'heroicon-s-arrow-small-down';

    protected static ?string $navigationGroup = "Laporan Keuangan";

    protected static ?string $pluralModelLabel = 'Pemasukan';
    protected static ?string $modelLabel = 'Pemasukan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date_income')
                    ->required(),
                Forms\Components\TextInput::make('source_of_income')
                    ->label('Jenis Kategori') // Mengubah label menjadi "Nama"
                    ->placeholder('Cash, Transfer, dll')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('categories_id')
                    ->label('Jenis Kategori Pemasukan')
                    ->relationship('category', 'name_of_categories', fn ($query) => $query->where('is_expense', 0))
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->label('Bukti Pemasukan')
                    ->acceptedFileTypes(['image/*', 'application/pdf']) // Izinkan gambar & PDF
                    ->storeFileNamesIn('file_name') // Simpan nama file jika diperlukan
                    ->directory('uploads/bukti-pemasukan') // Tentukan folder penyimpanan
                    ->downloadable(),
                Forms\Components\TextInput::make('no_invoice')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name_of_categories')
                    ->label('Deskripsi')
                    ->description(fn (Income $record): string => $record->description)
                    ->searchable(),
                Tables\Columns\TextColumn::make('source_of_income')
                    ->label('Sumber Pemasukan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->summarize(Sum::make()->label('Total Pemasukan')->money('IDR', locale: 'id')),
                Tables\Columns\TextColumn::make('date_income')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_invoice')
                    ->label('No Invoices')
                    ->default('-')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar Bukti')
                    ->getStateUsing(fn ($record) =>
                    Str::endsWith($record->image, ['.png', '.jpg', '.jpeg'])
                        ? asset('storage/' . $record->image) // Menampilkan gambar yang sudah ada
                        : asset('storage/pdf.png') // Jika PDF, tampilkan gambar default PDF
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('start_date')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Start Date'),
                        DatePicker::make('end_date')
                            ->label('End Date')
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['start_date'], fn ($q) => $q->whereDate('date_income', '>=', $data['start_date']))
                            ->when($data['end_date'], fn ($q) => $q->whereDate('date_income', '<=', $data['end_date']));
                    })
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export Income')
                    ->exporter(IncomeExporter::class)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(), // Tambahkan aksi View
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make()
                    ->exporter(IncomeExporter::class)
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
            'index' => Pages\ListIncomes::route('/'),
            'create' => Pages\CreateIncome::route('/create'),
            'edit' => Pages\EditIncome::route('/{record}/edit'),
        ];
    }
}
