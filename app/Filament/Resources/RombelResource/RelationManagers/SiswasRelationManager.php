<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use App\Models\Siswa;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\AttachAction;           // ✅ nama ruang-nama benar
use Filament\Actions\DetachAction;           // ✅
use Filament\Actions\DetachBulkAction;       // ✅
use Filament\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Builder;

class SiswasRelationManager extends RelationManager
{
    /** Nama relasi persis seperti di model Rombel */
    protected static string $relationship = 'siswas';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')

            // -----------------------  K O L O M  -----------------------
            ->columns([
                TextColumn::make('name')->label('Nama Siswa'),
            ])

            // -----------------  A T T A C H   (header)  -----------------
            ->headerActions([
                AttachAction::make()
                    ->multiple()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn (Builder $q) =>
                        $q->with([
                                'rombels:id,tingkat_id,jurusan_id,divisi',
                                'rombels.tingkat:id,nama_tingkat',
                                'rombels.jurusan:id,kode,nama_jurusan',
                            ])
                          ->select(['siswas.id', 'siswas.name'])
                          ->distinct()
                    )
                    ->recordTitle(function (Siswa $s) {
                        $label = $s->rombels->isNotEmpty()
                            ? $s->rombels
                                ->map(fn ($r) => "{$r->tingkat->nama_tingkat}-{$r->jurusan->kode}-" . ($r->divisi ?? '-'))
                                ->implode(', ')
                            : 'Rombel Belum Diatur';

                        return "{$s->name} — {$label}";
                    }),
            ])

            // ---------------  D E T A C H   (per-record)  ---------------
            ->recordActions([
                DetachAction::make(),          // ← ini yang menghapus baris pivot
            ])

            // ---------------  B U L K   D E T A C H ---------------------
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),  // ← untuk multi-select checkbox
                ]),
            ]);
    }
}
