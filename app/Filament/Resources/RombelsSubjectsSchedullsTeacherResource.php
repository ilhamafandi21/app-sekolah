<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Models\Subject;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\RombelsSubjectsSchedullsTeacherResource\Pages\ListRombelsSubjectsSchedullsTeachers;
use App\Filament\Resources\RombelsSubjectsSchedullsTeacherResource\Pages\CreateRombelsSubjectsSchedullsTeacher;
use App\Filament\Resources\RombelsSubjectsSchedullsTeacherResource\Pages\EditRombelsSubjectsSchedullsTeacher;
use Filament\Forms;
use Filament\Tables;
use App\Models\Rombel;
use Filament\Tables\Table;
use App\Models\RombelsSubjects;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Models\RombelsSubjectsSchedullsTeacher;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RombelsSubjectsSchedullsTeacherResource\Pages;
use App\Filament\Resources\RombelsSubjectsSchedullsTeacherResource\RelationManagers;
use App\Models\Schedull;
use Pest\Mutate\Options\IgnoreOption;

class RombelsSubjectsSchedullsTeacherResource extends Resource
{
    protected static ?string $model = RombelsSubjectsSchedullsTeacher::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::with([
            'rombel:id,kode,divisi,tingkat_id,jurusan_id',
            'rombel.jurusan:id,nama_jurusan,kode',
            'rombel.tingkat:id,nama_tingkat',
            'subject:id,name',
            'schedull:id,kode,start_at,end_at',
            'day:id,nama_hari',
            'teacher:id,name',
        ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('rombel_id')
                    ->required()
                    ->relationship(
                        name: 'rombel',
                        titleAttribute: 'kode',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->with(['jurusan:id,kode', 'tingkat:id,nama_tingkat'])
                    )
                    ->getOptionLabelFromRecordUsing(fn (Rombel $record) => sprintf(
                        '%s || %s %s-%s',
                            $record->kode,
                            $record->tingkat?->nama_tingkat ?? 'null',
                            $record->jurusan?->kode ?? 'null',
                            $record->divisi ?? 'null',
                    ))
                    ->reactive(),
                Select::make('subject_id')
                    ->label('Pilih Mapel')
                    ->required()
                    ->options(function (callable $get) {
                        $rombelId = $get('rombel_id');
                        if (!$rombelId) return [];
                        return Subject::whereHas('rombels', fn ($q) => $q->whereKey($rombelId))
                            ->orderBy('name')
                            ->pluck('name','id')
                            ->toArray();
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
                    ->label('RSID')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->reactive()
                    ->default(fn($get) => $get('subject_id')),
               
                Select::make('schedull_id')
                    ->label('Pilih Jam Pelajaran')
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
                    ->label('Pilih Jadwal Hari')
                    ->relationship(
                        name: 'day',
                        titleAttribute: 'nama_hari',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->select(['id','nama_hari']) // batasi kolom (wajib sertakan 'id')
                            ->orderBy('nama_hari')
                    )
                    ->required(),  

                Select::make('teacher_id')
                    ->label('Pengampu')
                    ->relationship(
                        name: 'teacher',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->select(['id','name']) // batasi kolom (wajib sertakan 'id')
                            ->orderBy('name')
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                static::getModel()::query()
                    ->with(['rombel:id,kode,divisi,tingkat_id,jurusan_id',
                            'rombel.tingkat:id,nama_tingkat', 
                            'rombel.jurusan:id,kode',
                            'subject:id,name',
                            'teacher:id,name',
                            'schedull:id,kode,start_at,end_at',
                            'day:id,nama_hari',
                            
                            ]) // eager load relasi
            )
            ->columns([
                TextColumn::make('kode')
                    ->label("RSSTID")
                    ->searchable(),
                TextColumn::make('rombels_subjects_id')
                    ->label("RSID")
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rombel.id')
                    ->formatStateUsing(function($record){
                        return $record->rombel->kode
                            .' '.$record->rombel->tingkat->nama_tingkat
                            .' '.$record->rombel->jurusan->kode
                            .'-'.$record->rombel->divisi;
                    })
                    ->sortable(),
                TextColumn::make('subject.name')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('day.id')
                    ->label('Hari')
                    ->formatStateUsing(function($record){
                        return $record->day->nama_hari;
                    })
                    ->sortable(),
                TextColumn::make('schedull.id')
                    ->formatStateUsing(function($record){
                        return $record->schedull->kode
                            .' '.$record->schedull->start_at
                            .' - '.$record->schedull->end_at;
                    })
                    ->sortable(),
                TextColumn::make('teacher.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListRombelsSubjectsSchedullsTeachers::route('/'),
            'create' => CreateRombelsSubjectsSchedullsTeacher::route('/create'),
            'edit' => EditRombelsSubjectsSchedullsTeacher::route('/{record}/edit'),
        ];
    }
}
