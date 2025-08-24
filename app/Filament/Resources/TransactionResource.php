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
                        modifyQueryUsing: fn (Builder $q) => $q
                            ->select(['id','kode','tingkat_id','jurusan_id','divisi'])
                            ->with([
                                'tingkat:id,nama_tingkat',   // ← ambil nama_tingkat
                                'jurusan:id,nama_jurusan',   // (opsional) kalau mau tampilkan kode jurusan
                            ])
                            ->orderBy('kode')
                    )
                    ->getOptionLabelFromRecordUsing(function (Rombel $r) {
                        return sprintf(
                            '%s || %s %s-%s',
                            $r->kode,
                            $r->tingkat?->nama_tingkat ?? '-',   // ← pakai relasi
                            $r->jurusan?->nama_jurusan ?? '-',           // atau ganti ke nama_jurusan kalau perlu
                            $r->divisi ?? '-',
                        );
                    })
                    ->searchable()
                    ->getSearchResultsUsing(function (string $term) {
                        return \App\Models\Rombel::query()
                            ->with(['tingkat:id,nama_tingkat','jurusan:id,kode'])
                            ->where('kode', 'like', "%{$term}%")
                            ->orWhereHas('tingkat', fn ($q) => $q->where('nama_tingkat', 'like', "%{$term}%"))
                            ->orWhereHas('jurusan', fn ($q) => $q->where('kode', 'like', "%{$term}%"))
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn ($r) => [
                                $r->id => sprintf('%s || %s %s-%s',
                                    $r->kode,
                                    $r->tingkat?->nama_tingkat ?? '-',
                                    $r->jurusan?->kode ?? '-',
                                    $r->divisi ?? '-',
                                ),
                            ])
                            ->toArray();
                    })
                    ->preload()
                    ->required(),

                Forms\Components\TextInput::make('biaya_id')
                    ->required()
                    ->numeric(),
                

                Forms\Components\Select::make('siswa_id')
                    ->options(function () {
                        return \App\Models\Siswa::all()->pluck('name', 'id');
                    })
                    ->required(),
                
                Forms\Components\TextInput::make('tingkat_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('jurusan_id')
                    ->required()
                    ->numeric(),
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
