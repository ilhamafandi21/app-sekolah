<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Rombel;
use Filament\Forms\Form;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('rombel_id')
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
                            $record->tingkat?->nama_tingkat ?? '-',
                            $record->jurusan?->kode ?? '-',
                            $record->divisi ?? '-',
                    ))
                    ->reactive(),
                Forms\Components\Select::make('subject_id')
                    ->label('Pilih Mapel')
                    ->required()
                    ->options(function (callable $get) {
                        $rombelId = $get('rombel_id');
                        if (!$rombelId) return [];
                        return \App\Models\Subject::whereHas('rombels', fn ($q) => $q->whereKey($rombelId))
                            ->orderBy('name')
                            ->pluck('name','id')
                            ->toArray();
                    })
                    ->reactive()
                    ->afterStateUpdated(function ($get, $set) {
                        $rombelId = $get('rombel_id');
                        $subjectId = $get('subject_id');

                        if ($rombelId && $subjectId) {
                            $pivot = \App\Models\RombelsSubjects::where('rombel_id', $rombelId)
                                ->where('subject_id', $subjectId)
                                ->first();
                            if ($pivot) {
                               $set('rombels_subjects_id', "$pivot->id");
                            }
                        }
                        return [];
                    }),
               
                Forms\Components\TextInput::make('rombels_subjects_id')
                    ->label('RSID')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->reactive()
                    ->default(fn($get) => $get('subject_id')),
               
                Forms\Components\Select::make('schedull_id')
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
                
                Forms\Components\Select::make('day_id')
                    ->label('Pilih Jadwal Hari')
                    ->relationship(
                        name: 'day',
                        titleAttribute: 'nama_hari',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->select(['id','nama_hari']) // batasi kolom (wajib sertakan 'id')
                            ->orderBy('nama_hari')
                    )
                    ->required(),  

                Forms\Components\Select::make('teacher_id')
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
                Tables\Columns\TextColumn::make('kode')
                    ->label("RSSTID")
                    ->searchable(),
                Tables\Columns\TextColumn::make('rombels_subjects_id')
                    ->label("RSID")
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rombel.id')
                    ->formatStateUsing(function($record){
                        return $record->rombel->kode
                            .' '.$record->rombel->tingkat->nama_tingkat
                            .' '.$record->rombel->jurusan->kode
                            .'-'.$record->rombel->divisi;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('day.id')
                    ->label('Hari')
                    ->formatStateUsing(function($record){
                        return $record->day->nama_hari;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('schedull.id')
                    ->formatStateUsing(function($record){
                        return $record->schedull->kode
                            .' '.$record->schedull->start_at
                            .' - '.$record->schedull->end_at;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRombelsSubjectsSchedullsTeachers::route('/'),
            'create' => Pages\CreateRombelsSubjectsSchedullsTeacher::route('/create'),
            'edit' => Pages\EditRombelsSubjectsSchedullsTeacher::route('/{record}/edit'),
        ];
    }
}
