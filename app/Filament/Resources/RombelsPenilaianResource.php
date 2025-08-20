<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Rombel;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RombelsSiswa;
use App\Models\RombelsPenilaian;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RombelsPenilaianResource\Pages;
use App\Filament\Resources\RombelsPenilaianResource\RelationManagers;
use App\Models\RombelsSubjects;

class RombelsPenilaianResource extends Resource
{
    protected static ?string $model = RombelsPenilaian::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Forms\Components\Select::make('rombel_id')
                    ->label('Pilih Rombel')
                    ->relationship(
                        name: 'rombel',
                        titleAttribute: 'kode',
                        // pastikan kolom yg dibutuhkan ikut di-select
                        modifyQueryUsing: function(Builder $query){
                           return $query
                                ->with([
                                    'tingkat:id,nama_tingkat',
                                    'jurusan:id,nama_jurusan,kode'
                                ])
                                ->select('id', 'kode', 'tingkat_id', 'jurusan_id', 'divisi');
                        } 
                    )
                     ->getOptionLabelFromRecordUsing(function ($record) {
                         return "{$record->kode} | 
                                {$record->tingkat->nama_tingkat} {$record->jurusan->kode}-{$record->divisi}";
                    })
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(fn (\Filament\Forms\Set $set) => $set('siswa_id', null))
                    ->required(),

                Forms\Components\Select::make('siswa_id')
                    ->label('Siswa')
                    ->options(function (Get $get): array {
                        $rombelId = $get('rombel_id');
                        if (blank($rombelId)) return [];

                        return RombelsSiswa::query()
                            ->with('siswa:id,name')               // eager load relasi siswa
                            ->where('rombel_id', $rombelId)
                            ->get()
                            ->unique('siswa_id')                  // pastikan distinct per siswa
                            ->sortBy(fn ($rs) => $rs->siswa?->name)
                            ->mapWithKeys(fn ($rs) => [
                                $rs->siswa_id => $rs->siswa?->name ?? "ID {$rs->siswa_id}"
                            ])
                            ->toArray();
                    })
                    ->disabled(fn ($get) => blank($get('rombel_id')))
                    ->searchable()
                    ->preload()                   // jangan preload sebelum rombel dipilih
                    ->required(),



                Forms\Components\Select::make('subject_id')
                    ->label('Subject')
                    ->options(function (Get $get): array {
                        $rombelId = $get('rombel_id');
                        if (blank($rombelId)) return [];

                        return RombelsSubjects::query()
                            ->with('subject:id,name')               // eager load relasi siswa
                            ->where('rombel_id', $rombelId)
                            ->get()
                            ->unique('subject_id')                  // pastikan distinct per siswa
                            ->sortBy(fn ($rs) => $rs->subject?->name)
                            ->mapWithKeys(fn ($rs) => [
                                $rs->subject_id => $rs->subject?->name ?? "ID {$rs->subject_id}"
                            ])
                            ->toArray();
                    })
                    ->disabled(fn ($get) => blank($get('rombel_id')))
                    ->searchable()
                    ->preload()                   // jangan preload sebelum rombel dipilih
                    ->required(),











                Forms\Components\Select::make('indikatornilai_id')
                    ->relationship('indikatornilai', 'id'),

                    
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name'),
                Forms\Components\Select::make('semester_id')
                    ->relationship('semester', 'name'),
                Forms\Components\TextInput::make('nilai')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rombel.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('siswa.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('indikatornilai.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nilai')
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
            'index' => Pages\ListRombelsPenilaians::route('/'),
            'create' => Pages\CreateRombelsPenilaian::route('/create'),
            'edit' => Pages\EditRombelsPenilaian::route('/{record}/edit'),
        ];
    }
}
