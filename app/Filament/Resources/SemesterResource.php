<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Semester;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use App\Filament\Resources\SemesterResource\Pages;

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
    ->label('Semester')
    ->options([
        'Ganjil' => 'Ganjil',
        'Genap' => 'Genap',
    ])
    ->required()
    ->native(false)
    ->afterStateUpdated(function ($state, callable $get, callable $set) {
        $tahunAjaranId = $get('tahun_ajaran_id');
        $editingId = request()->route('record');

        if (!$tahunAjaranId || !$state) {
            return;
        }

        $exists = \App\Models\Semester::query()
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->where('name', $state)
            ->when($editingId, fn ($q) => $q->where('id', '!=', $editingId))
            ->exists();

        if ($exists) {
            Notification::make()
                ->title('Semester sudah ada untuk tahun ajaran ini.')
                ->danger()
                ->persistent()
                ->send();

            $set('name', null); // reset isian agar user revisi
        }
    })
    ->helperText('Tidak boleh ada semester yang sama di tahun ajaran yang sama.'),


















            Forms\Components\DatePicker::make('start_date')
                ->label('Tanggal Mulai')
                ->required()
                ->helperText('Tanggal mulai harus lebih kecil dari tanggal selesai.')
                ->rule(function (callable $get) {
                        return function ($attribute, $value, $fail) use ($get) {
                            $end = $get('end_date');
                            if ($end && $value > $end) {
                                $fail('Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
                            }
                        };
                    }),

            Forms\Components\DatePicker::make('end_date')
                ->label('Tanggal Selesai')
                ->required()
                ->helperText('Tanggal selesai harus lebih besar dari tanggal mulai.')
                ->rule(function (callable $get) {
                        return function ($attribute, $value, $fail) use ($get) {
                            $start = $get('start_date');
                            if ($start && $value < $start) {
                                $fail('Tanggal selesai tidak boleh lebih kecil dari tanggal mulai.');
                            }
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
