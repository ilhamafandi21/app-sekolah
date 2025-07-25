<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SemesterResource\Pages;
use App\Models\Semester;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SemesterResource extends Resource
{
    protected static ?string $model = Semester::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Semester';
    protected static ?string $navigationGroup = 'Akademik';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('tahun_ajaran_id')
                ->label('Tahun Ajaran')
                ->relationship('tahunAjaran', 'name')
                ->preload()
                ->searchable()
                ->required(),

            Forms\Components\Select::make('name')
                ->label('Nama Semester')
                ->options([
                    'Ganjil' => 'Ganjil',
                    'Genap' => 'Genap',
                ])
                ->required()
                ->native(false),

            Forms\Components\DatePicker::make('start_date')
                ->label('Tanggal Mulai')
                ->required()
                ->rule(function (callable $get) {
                    return function ($value) use ($get) {
                        $endDate = $get('end_date');
                        if ($endDate && $value > $endDate) {
                            return 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.';
                        }
                        return null;
                    };
                }),

            Forms\Components\DatePicker::make('end_date')
                ->label('Tanggal Selesai')
                ->required()
                ->rule(function (callable $get) {
                    return function ($value) use ($get) {
                        $startDate = $get('start_date');
                        if ($startDate && $value < $startDate) {
                            return 'Tanggal selesai tidak boleh lebih kecil dari tanggal mulai.';
                        }
                        return null;
                    };
                }),

            Forms\Components\Toggle::make('is_active')
                ->label('Aktif')
                ->helperText('Centang jika semester ini sedang aktif'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('tahunAjaran.name')
                ->label('Tahun Ajaran')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('name')
                ->label('Semester')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('start_date')
                ->label('Mulai')
                ->date()
                ->sortable(),

            Tables\Columns\TextColumn::make('end_date')
                ->label('Selesai')
                ->date()
                ->sortable(),

            Tables\Columns\IconColumn::make('is_active')
                ->label('Aktif')
                ->boolean(),

            Tables\Columns\TextColumn::make('created_by')
                ->label('Dibuat Oleh')
                ->searchable(),

            Tables\Columns\TextColumn::make('updated_by')
                ->label('Diedit Oleh')
                ->searchable(),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Diperbarui')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSemesters::route('/'),
            'create' => Pages\CreateSemester::route('/create'),
            'edit' => Pages\EditSemester::route('/{record}/edit'),
        ];
    }
}
