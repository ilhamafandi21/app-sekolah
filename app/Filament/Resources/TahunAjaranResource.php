<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TahunAjaranResource\Pages;
use App\Models\TahunAjaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TahunAjaranResource extends Resource
{
    protected static ?string $model = TahunAjaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Tahun Ajaran';
    protected static ?string $navigationGroup = 'Akademik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Tahun Ajaran (YYYY-YYYY)')
                    ->maxLength(20)
                    ->required()
                    ->placeholder('Contoh: 2024-2025')
                    ->helperText('Format: 4 digit tahun - 4 digit tahun berikutnya')
                    ->rule('regex:/^\d{4}-\d{4}$/')
                    ->validationMessages([
                        'regex' => 'Format tahun ajaran harus seperti 2024-2025',
                    ])
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Bersihkan spasi & ubah ke uppercase
                        $cleaned = strtoupper(str_replace(' ', '', $state));
                        $set('name', $cleaned);
                    })
                    ->required()
                    ->rule(function () {
                        return function (string $attribute, $value, $fail) {
                            if (!preg_match('/^(\d{4})-(\d{4})$/', $value, $matches)) {
                                return; // Regex sudah ditangani di atas
                            }

                            $start = (int) $matches[1];
                            $end = (int) $matches[2];

                            if ($end !== $start + 1) {
                                $fail("Tahun kedua harus satu tahun setelah tahun pertama. Contoh: 2022-2023");
                            }
                        };
                    }),

                Forms\Components\DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->rule(function (callable $get) {
                        return function ($attribute, $value, $fail) use ($get) {
                            $end = $get('end_date');
                            if ($end && $value > $end) {
                                $fail('Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
                            }
                        };
                    })
                    ->required(),

                Forms\Components\DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->rule(function (callable $get) {
                        return function ($attribute, $value, $fail) use ($get) {
                            $start = $get('start_date');
                            if ($start && $value < $start) {
                                $fail('Tanggal selesai tidak boleh lebih kecil dari tanggal mulai.');
                            }
                        };
                    })
                    ->required(),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->helperText('Centang jika tahun ajaran ini aktif saat ini'),

                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi')
                    ->default('-')
                    ->rows(2)
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tahun Ajaran')
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

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(30)
                    ->wrap()
                    ->searchable(),

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
            'index' => Pages\ListTahunAjarans::route('/'),
            'create' => Pages\CreateTahunAjaran::route('/create'),
            'edit' => Pages\EditTahunAjaran::route('/{record}/edit'),
        ];
    }
}