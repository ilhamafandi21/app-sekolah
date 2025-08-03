<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RombelResource\Pages;
use App\Models\Rombel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RombelResource extends Resource
{
    protected static ?string $model = Rombel::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-right-start-on-rectangle';
    protected static ?string $navigationLabel = 'Rombel';

    public static function getEloquentQuery(): Builder
    {
       return static::getModel()::with([
            'tahun_ajaran:id,thn_ajaran',
            'tingkat:id,nama_tingkat',
            'jurusan:id,nama_jurusan',
        ]);
    }

   public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('🧾 Informasi Rombel')
                    ->description('Silakan lengkapi data rombongan belajar')
                    ->schema([
                        Forms\Components\Hidden::make('kode')
                            ->dehydrated()
                            ->nullable(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('tahun_ajaran_id')
                                    ->label('Tahun Ajaran')
                                    ->relationship('tahun_ajaran', 'thn_ajaran')
                                    ->disabled(fn (string $context) => $context === 'edit')
                                    ->reactive()
                                    ->required(),

                                Forms\Components\Select::make('tingkat_id')
                                    ->label('Tingkat')
                                    ->required()
                                    ->preload()
                                    ->reactive()
                                    ->disabled(fn (string $context, callable $get) =>
                                        $context === 'edit' || !$get('tahun_ajaran_id'))
                                    ->options(function (callable $get) {
                                        $tahunAjaranId = $get('tahun_ajaran_id');
                                        if (!$tahunAjaranId) return [];
                                        return \App\Models\Tingkat::where('tahun_ajaran_id', $tahunAjaranId)
                                            ->orderBy('nama_tingkat')
                                            ->pluck('nama_tingkat', 'id');
                                    }),

                                Forms\Components\Select::make('jurusan_id')
                                    ->label('Jurusan')
                                    ->required()
                                    ->reactive()
                                    ->disabled(fn (string $context, callable $get) =>
                                        $context === 'edit' || !$get('tingkat_id'))
                                    ->options(function (callable $get) {
                                        $tingkatId = $get('tingkat_id');
                                        if (!$tingkatId) return [];
                                        return \App\Models\Jurusan::where('tingkat_id', $tingkatId)
                                            ->orderBy('nama_jurusan')
                                            ->pluck('nama_jurusan', 'id');
                                    }),

                                Forms\Components\TextInput::make('divisi')
                                    ->label('Divisi')
                                    ->required()
                                    ->numeric()
                                    ->disabled(fn (string $context) => $context === 'edit'),
                            ])
                            ->columns(2),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('📌 Status & Keterangan')
                    ->description('Informasi tambahan')
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->label('Aktifkan Rombel')
                            ->default(true),

                        Forms\Components\TextInput::make('keterangan')
                            ->label('Catatan / Keterangan')
                            ->default('-')
                            ->nullable()
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('info_singkat')
                    ->label('Info Kelas')
                    ->getStateUsing(function ($record) {
                        return ($record->tingkat?->nama_tingkat ?? '-') . ' ' .
                                ($record->jurusan?->nama_jurusan ?? '-') . '-' .
                                ($record->divisi ?? '-');
                    })
                    ->wrap() // biar nggak terlalu panjang ke kanan
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_ajaran.thn_ajaran')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tingkat.nama_tingkat')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jurusan.nama_jurusan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('divisi')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ListRombels::route('/'),
            'create' => Pages\CreateRombel::route('/create'),
            'edit' => Pages\EditRombel::route('/{record}/edit'),
        ];
    }
}
