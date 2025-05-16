<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $pluralModelLabel = 'Kategori';
    protected static ?string $modelLabel = 'Kategori';




    protected static ?string $navigationIcon = 'heroicon-c-bars-3-center-left';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_of_categories')
                    ->label('Nama') // Mengubah label menjadi "Nama"
                    ->placeholder('Nama kategori..')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('is_expense')
                    ->label('Pemasukan/Pengeluaran') // Mengubah label
                    ->options([
                        0 => 'Pemasukan',
                        1 => 'Pengeluaran',
                    ]) // Menggunakan dropdown dengan opsi yang sesuai
                    ->required(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\IconColumn::make('is_expense')
                    ->label('Pemasukan atau Pengeluaran')
                    ->trueIcon('heroicon-o-arrow-up-circle')
                    ->falseIcon('heroicon-o-arrow-down-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->boolean(),

                Tables\Columns\TextColumn::make('name_of_categories')
                    ->label('Nama Kategori') // Mengubah label
                    ->searchable(),
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
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
