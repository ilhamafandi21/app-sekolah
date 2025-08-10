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
use Pest\Mutate\Options\IgnoreOption;

class RombelsSubjectsSchedullsTeacherResource extends Resource
{
    protected static ?string $model = RombelsSubjectsSchedullsTeacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                            ->with(['jurusan:id,nama_jurusan', 'tingkat:id,nama_tingkat'])
                    )
                    ->getOptionLabelFromRecordUsing(fn (Rombel $record) => sprintf(
                        '%s || %d %s-%s',
                        $record->kode,
                        $record->tingkat->nama_tingkat,
                        $record->jurusan->nama_jurusan,
                        $record->divisi,
                    ))
                    ->reactive(),
                Forms\Components\Select::make('subject_id')
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
                    ->label('Rombel Subjects ID')
                    ->required()
                    ->disabled()
                    ->dehydrated()
                    ->reactive()
                    ->default(fn($get) => $get('subject_id')),
               
                Forms\Components\Select::make('schedull_id')
                    ->required()
                    ->relationship(
                        name: 'schedull',
                        titleAttribute: 'kode',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->with(['schedull:id,kode,start_at,end_at'])
                    )
                    ->getOptionLabelFromRecordUsing(fn (Rombel $record) => sprintf(
                        '%s || %d %s-%s',
                        $record->kode,
                        $record->schedull->start_at,
                        $record->schedull->end_at,
                    )),
                
                Forms\Components\Select::make('day_id')
                    ->required()
                    ->relationship('day', 'nama_hari'),    

                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rombels_subjects_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rombel.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('schedull.id')
                    ->numeric()
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
