<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Siswa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
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
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
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
                            : new HtmlString('<span style="color:red;">Rombel Belum Diatur</span>');

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
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
