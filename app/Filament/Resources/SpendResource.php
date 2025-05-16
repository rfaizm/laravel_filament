<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Spend;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use App\Filament\Exports\SpendExporter;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\Resources\SpendResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SpendResource\RelationManagers;

class SpendResource extends Resource
{
    protected static ?string $model = Spend::class;

    protected static ?string $pluralModelLabel = 'Pengeluaran';
    protected static ?string $modelLabel = 'Pengeluaran';

    protected static ?string $navigationGroup = "Laporan Keuangan";

    protected static ?string $navigationIcon = 'heroicon-s-arrow-small-up';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date_spendings')
                    ->label('Tanggal')
                    ->required(),
                Forms\Components\TextInput::make('source_of_spending')
                    ->label('Jenis Kategori') // Mengubah label menjadi "Nama"
                    ->placeholder('Cash, Transfer, dll')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('total')
                    ->placeholder('Total harga')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('categories_id')
                    ->label('Jenis Kategori Pengeluaran')
                    ->relationship('category', 'name_of_categories', fn ($query) => $query->where('is_expense', 1))
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->label('Bukti Pengeluaran')
                    ->acceptedFileTypes(['image/*', 'application/pdf']) // Izinkan gambar & PDF
                    ->storeFileNamesIn('file_name') // Simpan nama file jika diperlukan
                    ->directory('uploads/bukti-pengeluaran') // Tentukan folder penyimpanan
                    ->downloadable()
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name_of_categories')
                    ->label('Deskripsi')
                    ->description(fn (Spend $record): string => $record->description)
                    ->searchable(),
                Tables\Columns\TextColumn::make('source_of_spending')
                    ->label('Sumber Pemasukan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->money('IDR', locale: 'id')
                    ->sortable()
                    ->summarize(Sum::make()->label('Total Pengeluaran')->money('IDR', locale: 'id')),
                Tables\Columns\TextColumn::make('date_spendings')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
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
                            ->when($data['start_date'], fn ($q) => $q->whereDate('date_spendings', '>=', $data['start_date']))
                            ->when($data['end_date'], fn ($q) => $q->whereDate('date_spendings', '<=', $data['end_date']));
                    }),

                
                
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Export Income')
                    ->exporter(SpendExporter::class)
                    ->options(function (array $data) {
                
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(), // Tambahkan aksi View
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListSpends::route('/'),
            'create' => Pages\CreateSpend::route('/create'),
            'edit' => Pages\EditSpend::route('/{record}/edit'),
        ];
    }
}
