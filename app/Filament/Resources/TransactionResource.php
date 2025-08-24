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

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode')
                    ->required(),


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
                            $set('tingkat_id', $rombel?->tingkat?->nama_tingkat),
                            $set('jurusan_id', $rombel?->jurusan?->kode),
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
                
                Forms\Components\TextInput::make('tingkat_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('jurusan_id')
                    ->required(),
                Forms\Components\TextInput::make('divisi')
                    ->required(),
                Forms\Components\TextInput::make('nominal')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('keterangan')
                    ->default('Pembayaran Biaya Pendidikan')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('siswa_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('biaya_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rombel_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tingkat_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jurusan_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('divisi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nominal')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
