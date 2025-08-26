<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Rombel;
use Filament\Forms\Form;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              

                Forms\Components\Select::make('rombel_id')
                    ->relationship(
                        name: 'rombel',
                        titleAttribute: 'kode',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->select(['id','kode','tingkat_id','jurusan_id','divisi'])
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

                        $rombel = \App\Models\Rombel::query()
                            ->select(['id','tingkat_id', 'jurusan_id', 'divisi'])
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

                Forms\Components\Select::make('biaya_id')
                    ->options(function ($get) {
                        $rombelId = $get('rombel_id');
                        return \App\Models\RombelBiaya::where('rombel_id', $rombelId)
                            ->with(['biaya:id,name,nominal'])        // batasi kolom
                            ->get()
                            ->mapWithKeys(fn ($row) => [
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
                

                Forms\Components\Select::make('siswa_id')
                    ->options(function ($get) {
                        $rombelId = $get('rombel_id');
                        return \App\Models\RombelsSiswa::where('rombel_id', $rombelId)
                            ->with('siswa:id,name')
                            ->get()
                            ->pluck('siswa.name', 'siswa.id');
                    })
                    ->preload()
                    ->required(),
                
                Forms\Components\Hidden::make('tingkat_id')
                    ->required()
                    ->dehydrated(true),
                Forms\Components\Hidden::make('jurusan_id')
                    ->required()
                    ->dehydrated(true),
                Forms\Components\Hidden::make('divisi')
                    ->required()
                    ->dehydrated(true),
                Forms\Components\Select::make('semester')
                    ->options(
                        \App\Models\Semester::query()
                            ->pluck('name', 'id') // [id => name]
                    )
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('nominal')
                    ->label('Jumlah Bayar')
                    ->required()
                    ->numeric(),

               Forms\Components\Toggle::make('status')
                    ->label('Status Bayar')
                    ->onIcon('heroicon-o-check-circle')
                    ->offIcon('heroicon-o-x-circle')
                    ->onColor('success')     // hijau kalau ON
                    ->offColor('danger')     // merah kalau OFF
                    ->required()
                    ->inline(false) // biar ada label di samping
                    ->helperText('Tandai Lunas jika pembayaran sudah lunas'),

                Forms\Components\TextInput::make('keterangan')
                    ->default('Pembayaran Biaya Pendidikan')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Static::getModel()::query()
                ->with(['siswa:id,name',
                        'biaya:id,name,nominal',
                        'tingkat:id,nama_tingkat',
                        'jurusan:id,kode'
                        ]))
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->limit(5)
                    ->searchable(),
                Tables\Columns\TextColumn::make('siswa.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('biaya.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ringkasan_rombel')
                    ->label('Rombel')
                    ->state(function ($record) {
                        $tingkat = $record->tingkat?->nama_tingkat ?? '-';
                        $jurusan = $record->jurusan?->kode ?? '-';
                        $divisi  = $record->divisi ?? '-';
                        return "{$tingkat} {$jurusan}-{$divisi}";
                    })
                    ->searchable(query: function (Builder $q, string $term) {
                        $q->whereHas('tingkat', fn ($x) => $x->where('nama_tingkat', 'like', "%{$term}%"))
                        ->orWhereHas('jurusan', fn ($x) => $x->where('kode', 'like', "%{$term}%"))
                        ->orWhere('divisi', 'like', "%{$term}%");
                }),

                Tables\Columns\TextColumn::make('biaya.nominal')
                    ->label('Nominal Biaya')
                    ->color('info')
                    ->money('IDR', true, locale: 'id_ID')
                    ->sortable(),
               
                Tables\Columns\TextColumn::make('nominal')
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

                Tables\Columns\TextColumn::make('status')
                    ->label('Status Bayar')
                    ->formatStateUsing(fn ($state) => $state ? 'Lunas' : 'Belum Lunas')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('keterangan')
                    ->limit(10)
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
