<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use App\Models\Rombel;
use App\Models\Semester;
use Filament\Tables\Table;
use App\Models\RombelBiaya;
use App\Models\RombelsSiswa;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Actions\DissociateBulkAction;
use Filament\Resources\RelationManagers\RelationManager;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('rombel_id')
                    ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->id)
                    ->disabled()
                    ->dehydrated()
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
                    ->live()
                    ->preload()
                    ->reactive()
                    ->required(),

                Select::make('siswa_id')
                    ->options(function ($get) {
                        $rombelId = $get('rombel_id');
                        return RombelsSiswa::where('rombel_id', $rombelId)
                            ->with('siswa:id,name')
                            ->get()
                            ->pluck('siswa.name', 'siswa.id');
                    })
                    ->live()
                    ->preload()
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(fn (callable $set) => $set('kode', 'halo'
                    )),

                TextInput::make('kode')
                    ->disabled()
                    ->required()
                    ->dehydrated(),
                TextInput::make('tingkat_id')
                    ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->tingkat_id)
                    ->required()
                    ->dehydrated(),
                TextInput::make('jurusan_id')
                    ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->jurusan_id)
                    ->required()
                    ->dehydrated(),
                TextInput::make('divisi')
                    ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->divisi)
                    ->required()
                    ->dehydrated(),
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

                TextInput::make('keterangan')
                    ->default('Pembayaran Biaya Pendidikan')
                    ->required(),
                ]);

    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('kode')
            ->columns([
                TextColumn::make('kode')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ,
                // AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                // DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
