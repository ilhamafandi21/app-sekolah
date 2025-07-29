<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Subject;
use App\Models\Teacher;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\SemesterEnum;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

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
                'teachers:id,name',

            ])
            ->select([
                'id','name','semester_id','tingkat_id','jurusan_id','divisi','status','keterangan','created_at','updated_at',
            ]);
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Guru Pengajar')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('teacher_id')
                                    ->label('Guru Pengajar')
                                    ->options(
                                        Teacher::query()
                                            ->select(['id', 'name'])
                                            ->orderBy('name')
                                            ->pluck('name', 'id')
                                    )
                                    ->searchable(),
                            ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query
                    ->select(['subjects.id', 'subjects.name', 'semester_id']) ;
            })

            ->columns([
                Tables\Columns\TextColumn::make('name'),

                Tables\Columns\TextColumn::make('semester_id')
                    ->formatStateUsing(fn ($state) => SemesterEnum::from($state)->label())
                    ->label('Semester'),
                
                Tables\Columns\TextColumn::make('Guru Pengajar')
                    ->label('Guru Pengajar')
            ])

            ->headerActions([
                AttachAction::make()
                    ->label('Tambah Mata Pelajaran')
                    ->form(fn (AttachAction $action) => [
                        $action->getRecordSelect()
                            ->label('Mata Pelajaran')
                            ->multiple()
                            ->searchable()
                            ->options(function () {
                                    $rombelId = $this->getOwnerRecord()?->id;

                                    return Subject::query()
                                        ->select(['id', 'name'])
                                        ->whereDoesntHave('rombels_subjects', fn ($q) =>
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
                    ])
                    ->recordTitleAttribute('name'),
            ])

            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Hapus Mata Pelajaran')
                    ->requiresConfirmation()
                    ->successNotificationTitle('Mata Pelajaran berhasil dihapus dari Rombel'),

                Tables\Actions\EditAction::make()
                ->form([
        Forms\Components\Select::make('teacher_id')
            ->label('Guru Pengajar')
            ->options(
                Teacher::query()
                    ->orderBy('name')
                    ->pluck('name', 'id')
            )
            ->searchable()
            ->required(),
    ])
                  
            ])  
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
        }
   
}
