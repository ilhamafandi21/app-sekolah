<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Models\RombelBiaya;
use App\Models\RombelsSiswa;
use Filament\Forms\Components\Hidden;
use App\Models\Semester;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TransactionResource\Pages\ListTransactions;
use App\Filament\Resources\TransactionResource\Pages\CreateTransaction;
use App\Filament\Resources\TransactionResource\Pages\EditTransaction;
use Filament\Forms;
use Filament\Tables;
use App\Models\Rombel;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([


                Select::make('rombel_id')
                    ->relationship(
                        name: 'rombel',
                        titleAttribute: 'kode',
                        modifyQueryUsing: fn(Builder $query) => $query
                            ->select(['id', 'kode', 'tingkat_id', 'jurusan_id', 'divisi'])
                            ->with([
                                'tingkat:id,nama_tingkat',   // ← ambil nama_tingkat
                                'jurusan:id,kode',   // (opsional) kalau mau tampilkan kode jurusan
                            ])
                            ->orderBy('kode')
                    )
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        return "{$record->kode} |
                                {$record->tingkat->nama_tingkat} {$record->jurusan->kode}-{$record->divisi}";
                    })
                    ->afterStateUpdated(function ($set, $state) {
                        // setiap ganti rombel, kosongkan siswa_id

                        $set('tingkat_id', null);
                        $set('jurusan_id', null);
                        $set('divisi', null);

                        if (!$state) return;

                        $rombel = Rombel::query()
                            ->select(['id', 'tingkat_id', 'jurusan_id', 'divisi'])
                            ->with([
                                'tingkat:id,nama_tingkat',
                                'jurusan:id,kode',
                            ])
                            ->find($state);

                        return [
                            $set('siswa_id', null),
                            $set('biaya_id', null),
                            $set('tingkat_id', $rombel?->tingkat?->id),
                            $set('jurusan_id', $rombel?->jurusan?->id),
                            $set('divisi', $rombel?->divisi)
                        ];
                    })
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->required(),

                Select::make('biaya_id')
                    ->options(function ($get) {
                        $rombelId = $get('rombel_id');
                        return RombelBiaya::where('rombel_id', $rombelId)
                            ->with(['biaya:id,name,nominal'])        // batasi kolom
                            ->get()
                            ->mapWithKeys(fn($row) => [
                                $row->biaya->id => sprintf(
                                    '%s — Rp %s',
                                    $row->biaya->name,
                                    number_format((int) $row->biaya->nominal, 0, ',', '.')
                                ),
                            ])
                            ->toArray();
                    })


                    ->preload()
                    ->required(),


                Select::make('siswa_id')
                    ->options(function ($get) {
                        $rombelId = $get('rombel_id');
                        return RombelsSiswa::where('rombel_id', $rombelId)
                            ->with('siswa:id,name')
                            ->get()
                            ->pluck('siswa.name', 'siswa.id');
                    })
                    ->preload()
                    ->required(),

                Hidden::make('tingkat_id')
                    ->required()
                    ->dehydrated(true),
                Hidden::make('jurusan_id')
                    ->required()
                    ->dehydrated(true),
                Hidden::make('divisi')
                    ->required()
                    ->dehydrated(true),
                Select::make('semester')
                    ->options(
                        Semester::query()
                            ->pluck('name', 'id') // [id => name]
                    )
                    ->searchable()
                    ->required(),
                TextInput::make('nominal')
                    ->label('Jumlah Bayar')
                    ->required()
                    ->numeric(),

                Toggle::make('status')
                    ->label('Status Bayar')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')     // hijau kalau ON
                    ->offColor('danger')     // merah kalau OFF
                    ->required()
                    ->inline(false) // biar ada label di samping
                    ->helperText('Tandai Lunas jika pembayaran sudah lunas'),

                TextInput::make('keterangan')
                    ->default('Pembayaran Biaya Pendidikan')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(static::getModel()::query()
                ->with([
                    'siswa:id,name',
                    'biaya:id,name,nominal',
                    'tingkat:id,nama_tingkat',
                    'jurusan:id,kode'
                ]))
            ->columns([
                TextColumn::make('kode')
                    ->limit(5)
                    ->searchable(),
                TextColumn::make('siswa.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('biaya.name')
                    ->sortable(),
                TextColumn::make('ringkasan_rombel')
                    ->label('Rombel')
                    ->state(function ($record) {
                        $tingkat = $record->tingkat?->nama_tingkat ?? '-';
                        $jurusan = $record->jurusan?->kode ?? '-';
                        $divisi  = $record->divisi ?? '-';
                        return "{$tingkat} {$jurusan}-{$divisi}";
                    })
                    ->searchable(query: function (Builder $q, string $term) {
                        $q->whereHas('tingkat', fn($x) => $x->where('nama_tingkat', 'like', "%{$term}%"))
                            ->orWhereHas('jurusan', fn($x) => $x->where('kode', 'like', "%{$term}%"))
                            ->orWhere('divisi', 'like', "%{$term}%");
                    }),

                TextColumn::make('biaya.nominal')
                    ->label('Nominal Biaya')
                    ->color('info')
                    ->money('IDR', true, locale: 'id_ID')
                    ->sortable(),

                TextColumn::make('nominal')
                    ->label('Jumlah Bayar')
                    ->color('success')
                    ->money('IDR', true, locale: 'id_ID')
                    ->sortable(),

                // Tables\Columns\TextColumn::make('tunggakan')
                //     ->label('Tunggakan')
                //     ->color('warning')
                //     ->money('IDR', true, locale: 'id_ID')
                //     ->sortable()
                //     ->getStateUsing(function ($record) {
                //         return max(0, $record->biaya->nominal - $record->nominal);
                //     }),

                TextColumn::make('status')
                    ->label('Status Bayar')
                    ->formatStateUsing(fn($state) => $state ? 'Lunas' : 'Belum Lunas')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger')
                    ->sortable(),

                TextColumn::make('keterangan')
                    ->limit(10)
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListTransactions::route('/'),
            'create' => CreateTransaction::route('/create'),
            'edit' => EditTransaction::route('/{record}/edit'),
        ];
    }
}
