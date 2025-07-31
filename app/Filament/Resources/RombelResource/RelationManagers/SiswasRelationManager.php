<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Siswa;
use App\Models\Rombel;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AttachAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SiswasRelationManager extends RelationManager
{
    protected static string $relationship = 'siswas';

    public static function modifyQueryUsing(Builder $query): Builder
    {
        return $query->with([
            'user',             // jika ingin tampilkan nama user
            'documents',        // jika ingin hitung dokumen atau tampilkan
            'rombelsSiswas',    // relasi pivot ke rombel (untuk akses semester_id, dll)
            'rombelsSiswas.rombel', // jika perlu akses info rombel juga
        ]);
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

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
                AttachAction::make()
                    ->label('Tambah Siswa')
                    ->form(fn (AttachAction $action) => [
                        $action->getRecordSelect()
                            ->label('Tambah Siswa')
                            ->multiple()
                            ->searchable()
                            ->options(function () {
                                    $rombelId = $this->getOwnerRecord()?->id;

                                    return Siswa::query()
                                        ->select(['id', 'name'])
                                        ->whereDoesntHave('rombels', fn ($q) =>
                                            $q->where('rombel_id', $rombelId)
                                        )
                                        ->orderBy('name')
                                        ->pluck('name', 'id')
                                        ->all();
                                    }
                            ),

                        Forms\Components\TextInput::make('semester_id')
                            ->label('Semester')
                            ->readOnly()
                            ->disabled()
                            ->dehydrated(true)
                            ->default(fn () => $this->getOwnerRecord()?->semester_id)
                            ->required(),

                        Forms\Components\TextInput::make('divisi')
                            ->label('Divisi')
                            ->readOnly()
                            ->disabled()
                            ->dehydrated(true)
                            ->default(fn () => $this->getOwnerRecord()?->divisi)
                            ->required(),


                         Forms\Components\TextInput::make('tingkat_id')
                            ->label('Tingkat')
                            ->readOnly()
                            ->disabled()
                            ->dehydrated(true)
                            ->default(fn () => $this->getOwnerRecord()?->tingkat_id)
                            ->required(),

                        Forms\Components\TextInput::make('jurusan_id')
                            ->label('Jurusan')
                            ->readOnly()
                            ->disabled()
                            ->dehydrated(true)
                            ->default(fn () => $this->getOwnerRecord()?->jurusan_id)
                            ->required(),

                    
                    ])
                    ->recordTitleAttribute('name'),
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
