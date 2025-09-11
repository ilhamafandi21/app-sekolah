<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use App\Models\Rombel;
use App\Models\Semester;
use Filament\Tables\Table;
use App\Models\RombelBiaya;
use App\Models\RombelsSiswa;
use App\Models\SiswaBiaya;
use Dom\Text;
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

                Hidden::make('rombel_id')
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
                     ->afterStateUpdated(function ($state, callable $set) {
                        // reset siswa dan kode saat biaya diganti
                        $set('siswa_id', null);
                        $set('kode', null);
                    })
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
                    ->disabled(fn ($get) => ! $get('biaya_id'))
                    ->preload()
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(fn (callable $set, $get) => $set('kode', $this->getOwnerRecord()->id
                                                            . $get('biaya_id')
                                                            . $get('siswa_id')
                                                            . $this->getOwnerRecord()->tingkat_id
                                                            . $this->getOwnerRecord()->jurusan_id
                                                            . $this->getOwnerRecord()->divisi
                                                            . date('YmdHis')
                    )),

                Hidden::make('kode')
                    ->disabled()
                    ->required()
                    ->dehydrated(),
                Hidden::make('tingkat_id')
                    ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->tingkat_id)
                    ->required()
                    ->dehydrated(),
                Hidden::make('jurusan_id')
                    ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->jurusan_id)
                    ->required()
                    ->dehydrated(),
                Hidden::make('divisi')
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
        ->recordTitleAttribute('biaya.name')
        ->modifyQueryUsing(fn ($query) =>
           $query->join('biayas', 'transactions.biaya_id', '=', 'biayas.id')
                ->select('transactions.*')
                ->groupBy('transactions.biaya_id', 'transactions.siswa_id')
        )
        ->columns([
            TextColumn::make('siswa.name')
                ->label('Siswa'),

            TextColumn::make('biaya.name')
                ->label('Biaya'),

            TextColumn::make('status')
                ->default(fn ($record) =>
                    SiswaBiaya::where('siswa_id', $record->siswa_id)
                        ->where('biaya_id', $record->biaya_id)
                        ->value('status') == 1
                        ? 'Lunas'
                        : 'Belum Lunas'
                )
                ->badge()
                ->color(fn ($state) => $state === 'Lunas' ? 'success' : 'danger')
                ->label('Status Bayar'),
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
