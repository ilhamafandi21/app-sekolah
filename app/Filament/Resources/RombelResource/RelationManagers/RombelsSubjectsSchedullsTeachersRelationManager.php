<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Rombel;
use App\Models\Schedull;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class RombelsSubjectsSchedullsTeachersRelationManager extends RelationManager
{
    protected static string $relationship = 'RombelsSubjectsSchedullsTeachers';


    public function form(Form $form): Form
   {

        return $form
            ->schema([
                Forms\Components\TextInput::make('rombel_id')
                    ->required()
                    ->default($this->getOwnerRecord()->id)
                    ->disabled()
                    ->dehydrated()
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
                            ->select(['id','kode','start_at','end_at']) // batasi kolom (wajib sertakan 'id')
                            ->orderBy('start_at')
                    )
                    ->getOptionLabelFromRecordUsing(function (Schedull $s) {
                        $start = $s->start_at ? substr($s->start_at, 0, 5) : '-';
                        $end   = $s->end_at   ? substr($s->end_at, 0, 5)   : '-';
                        return "{$s->kode} — {$start} s/d {$end}";
                    }),
                
                Forms\Components\Select::make('day_id')
                    ->required()
                    ->relationship('day', 'nama_hari'),    

                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('day.nama_hari'),
                Tables\Columns\TextColumn::make('subject.name'),
                Tables\Columns\TextColumn::make('schedull.kode'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function(array $data){
                        $kodeGabungan = $data['rombel_id'].
                                        $data['subject_id'].
                                        $data['rombels_subjects_id'].
                                        $data['schedull_id'].
                                        $data['day_id'];

                        $cekduplikat = \App\Models\RombelsSubjectsSchedullsTeacher::where('rombel_id', $data['rombel_id'])
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

                            
                        }

                        $data['kode'] = $kodeGabungan;

                        dd($data);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
