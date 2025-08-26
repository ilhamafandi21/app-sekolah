<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use RuntimeException;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Models\RombelsSubjects;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use App\Models\RombelsSubjectsSchedullsTeacher;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Tables;
use App\Models\Rombel;
use App\Models\Schedull;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class RombelsSubjectsSchedullsTeachersRelationManager extends RelationManager
{
    protected static string $relationship = 'rombelsSubjectsSchedullsTeachers';
    protected static ?string $title = 'Atur Jadwal';

    // public function mount(): void
    // {
    //     DB::enableQueryLog();

    //     $records = $this->getTableQuery()->get(); // atau getTableQuery() yang sudah kamu tulis
    //     dd(DB::getQueryLog());
    // }

    protected function getTableQuery(): Builder
    {
        $owner = $this->getOwnerRecord(); // Rombel
        if (! $owner) {
            // kalau ini terpanggil, kamu membuka RM dari halaman yang bukan View/Edit Rombel
            throw new RuntimeException('Owner record null. Buka RelationManager dari halaman View/Edit Rombel.');
        }

        // kunci di relasi owner, lalu eager-load yang dipakai kolom
        return $owner->rombelsSubjectsSchedullsTeachers()
            ->getQuery()
            ->with([
                'day:id,nama_hari',
                'subject:id,name',
                'schedull:id,kode,start_at,end_at',
                'teacher:id,name',
            ])
            ->orderBy('day_id')
            ->orderBy(
                Schedull::select('start_at')
                    ->whereColumn('schedulls.id', 'rombels_subjects_schedulls_teachers.schedull_id')
            );
    }


    public function form(Schema $schema): Schema
    {

        return $schema
            ->components([
                TextInput::make('rombel_id')
                    ->required()
                    ->default($this->getOwnerRecord()->id)
                    ->disabled()
                    ->dehydrated()
                    ->reactive(),
                Select::make('subject_id')
                    ->required()
                    ->options(function (callable $get) {
                        $rombelId = $get('rombel_id');
                        if (!$rombelId) return [];
                        $rombel = Rombel::with('subjects')->find($rombelId);
                        return $rombel
                            ? $rombel->subjects->pluck('name', 'id')->toArray()
                            : [];
                    })
                    ->reactive()
                    ->afterStateUpdated(function ($get, $set) {
                        $rombelId = $get('rombel_id');
                        $subjectId = $get('subject_id');

                        if ($rombelId && $subjectId) {
                            $pivot = RombelsSubjects::where('rombel_id', $rombelId)
                                ->where('subject_id', $subjectId)
                                ->first();
                            if ($pivot) {
                               $set('rombels_subjects_id', "$pivot->id");
                            }
                        }
                        return [];
                    }),
               
                TextInput::make('rombels_subjects_id')
                    ->label('Rombel Subjects ID')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->reactive()
                    ->default(fn($get) => $get('subject_id')),
               
                Select::make('schedull_id')
                    ->required()
                    ->relationship(
                        name: 'schedull',
                        titleAttribute: 'kode',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->select(['id','kode','start_at','end_at']) // batasi kolom (wajib sertakan 'id')
                            ->orderBy('start_at')
                    )
                    ->getOptionLabelFromRecordUsing(function (Schedull $s) {
                        $start = $s->start_at ? substr($s->start_at, 0, 5) : '-';
                        $end   = $s->end_at   ? substr($s->end_at, 0, 5)   : '-';
                        return "{$s->kode} — {$start} s/d {$end}";
                    }),
                
                Select::make('day_id')
                    ->required()
                    ->relationship('day', 'nama_hari'),    

                Select::make('teacher_id')
                    ->relationship('teacher', 'name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            // 2) Eager load SEMUA relasi yang dipakai kolom

            ->groups([
                Group::make('day_id')
                    ->label('Hari')
                    ->getTitleFromRecordUsing(fn ($record) => $record->day->nama_hari ?? '—')
                    ->collapsible(),
            ])
            ->defaultGroup('day_id')
            ->columns([
                TextColumn::make('')
                    ->description(fn($record)=> $record->day->nama_hari)
                    ->default('--->'),
                TextColumn::make('subject.name'),
                TextColumn::make('schedull.kode')
                    ->label('Jadwal')
                    ->getStateUsing(function ($record) {
                        $kode  = $record->schedull->kode ?? '-';
                        $start = $record->schedull->start_at ? substr($record->schedull->start_at, 0, 5) : '-';
                        $end   = $record->schedull->end_at   ? substr($record->schedull->end_at, 0, 5)   : '-';

                        return "{$kode} — {$start} s/d {$end}";
                    }),
                TextColumn::make('teacher.name')
                    ->badge()
                    ->html()
                    ->getStateUsing(function ($record) {
                        $teacher  = $record->teacher->name ?? "<span style='color:#f66;'>Belum ditetapkan</span>";
                        return "{$teacher}";
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Buat Jadwal')
                    ->color('info')
                    ->mutateDataUsing(function(array $data){
                        $kodeGabungan = $data['rombel_id'].
                                        $data['subject_id'].
                                        $data['rombels_subjects_id'].
                                        $data['schedull_id'].
                                        $data['day_id'];

                        $cekduplikat = RombelsSubjectsSchedullsTeacher::where('rombel_id', $data['rombel_id'])
                                    ->where('subject_id', $data['subject_id'])
                                    ->where('rombels_subjects_id', $data['rombels_subjects_id'])
                                    ->where('schedull_id', $data['schedull_id'])
                                    ->where('day_id', $data['day_id'])
                                    ->exists();

                        if($cekduplikat){
                            Notification::make()
                                ->title('Error')
                                ->body('Data sudah ada')
                                ->danger()
                                ->send();

                            throw ValidationException::withMessages([
                                'kode' => 'Data duplikat tidak dapat disimpan.',
                            ]);
                        }

                        $data['kode'] = $kodeGabungan;

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
