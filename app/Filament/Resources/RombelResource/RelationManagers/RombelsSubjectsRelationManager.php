<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use App\Enums\SemesterEnum;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RombelsSubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'rombels_subjects';
    protected static ?string $inverseRelationship = 'rombels_subjects';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'jurusan:id,nama,kode',
                'semester:id,name,tahun_ajaran_id',
                'semester.tahunAjaran:id,thn_ajaran',
            ])
            ->select([
                'id','name','semester_id','tingkat_id','jurusan_id','divisi','status','keterangan','created_at','updated_at',
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            /**
             * Pastikan query relasi (subjects) hanya memilih kolom yang dipakai
             * dan TIDAK melakukan lazy loading tambahan.
             */
            ->modifyQueryUsing(function (Builder $query) {
                $query
                    ->select(['subjects.id', 'subjects.name']) // minimal kolom
                    // jika Anda butuh hitung total, dsb, tambahkan di sini agar tetap 1 query
                    ;
            }) // :contentReference[oaicite:1]{index=1}

            ->columns([
                Tables\Columns\TextColumn::make('name'),

                Tables\Columns\TextColumn::make('pivot.semester_id')
                    ->label('Semester')
                    ->formatStateUsing(
                        fn ($state) => SemesterEnum::tryFrom((int) $state)?->label() ?? '-'
                    ),
            ])

            ->headerActions([
                AttachAction::make()
                    ->label('Tambah Mata Pelajaran')
                    /**
                     * Gunakan opsi manual ->options(...) agar Filament tidak
                     * membangun query otomatis (yang sempat memicu qualifyColumn() null).
                     * Ini 1 query terkontrol, tanpa N+1.
                     */
                    ->form(fn (AttachAction $action) => [
                        $action->getRecordSelect()
                            ->label('Mata Pelajaran')
                            ->searchable()
                            ->options(
                                Subject::query()
                                    ->select(['id', 'name'])
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                                    ->all()
                            ),

                        Forms\Components\TextInput::make('semester_id')
                            ->label('Semester')
                            ->readOnly()
                            ->default(fn () => $this->getOwnerRecord()?->semester_id)
                            ->required(),
                    ])
                    ->recordTitleAttribute('name'),
            ])

            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\Select::make('semester_id')
                            ->label('Semester')
                            ->options(SemesterEnum::options())
                            ->required(),
                        Forms\Components\TextInput::make('keterangan')
                            ->maxLength(255),
                    ]),
                Tables\Actions\DetachAction::make(),
            ])

            // jika 1 subject boleh di-attach beberapa kali (beda semester)
            ->allowDuplicates(); // :contentReference[oaicite:2]{index=2}
    }
}
