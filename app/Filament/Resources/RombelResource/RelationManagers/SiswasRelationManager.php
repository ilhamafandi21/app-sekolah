<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachBulkAction;
use Filament\Forms;
use Filament\Tables;
use App\Models\Siswa;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SiswasRelationManager extends RelationManager
{
    protected static string $relationship = 'siswas';



    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->multiple()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(function (Builder $query) {
                        return $query
                            ->with([
                                // rombels dan relasi yang dibutuhkan untuk label
                                'rombels:id,tingkat_id,jurusan_id,divisi',
                                'rombels.tingkat:id,nama_tingkat',
                                'rombels.jurusan:id,kode,nama_jurusan',
                            ])
                            ->select(['siswas.id','siswas.name']) // kwalifikasi kolom biar tidak ambiguous
                            ->distinct();
                    })
                    ->recordTitle(function (Siswa $record): string {
                        // v1: sesuai permintaan — gabungan ID/field mentah
                        $labelRombel = $record->rombels->isNotEmpty()
                            ? $record->rombels
                                ->map(fn ($r) => "{$r->tingkat->nama_tingkat}-{$r->jurusan->kode}-" . ($r->divisi ?? '-'))
                                ->implode(', ')
                            : 'Rombel Belum Diatur';

                        // --- Jika ingin versi "cantik", pakai ini sebagai ganti v1:
                        // $labelRombel = $record->rombels->isNotEmpty()
                        //     ? $record->rombels
                        //         ->map(function ($r) {
                        //             $tingkat = $r->tingkat->nama_tingkat ?? $r->tingkat_id;
                        //             $jurusan = $r->jurusan->kode ?? $r->jurusan_id;
                        //             $divisi  = $r->divisi ?? '-';
                        //             return "{$tingkat}-{$jurusan}-{$divisi}";
                        //         })
                        //         ->implode(', ')
                        //     : 'Rombel belum diatur';

                        return "{$record->name} — {$labelRombel}";
                    }),

            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
