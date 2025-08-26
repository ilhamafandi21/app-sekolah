<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\RombelsPenilaianResource\Pages\ListRombelsPenilaians;
use App\Filament\Resources\RombelsPenilaianResource\Pages\CreateRombelsPenilaian;
use App\Filament\Resources\RombelsPenilaianResource\Pages\EditRombelsPenilaian;
use Filament\Forms;
use Filament\Tables;
use App\Models\Rombel;
use Filament\Tables\Table;
use App\Models\RombelsSiswa;
use App\Models\RombelsPenilaian;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RombelsPenilaianResource\Pages;
use App\Filament\Resources\RombelsPenilaianResource\RelationManagers;
use App\Models\RombelsSubjects;
use App\Models\SubjectsIndikatornilai;

class RombelsPenilaianResource extends Resource
{
    protected static ?string $model = RombelsPenilaian::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
               Select::make('rombel_id')
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
                    ->afterStateUpdated(fn (Set $set) => $set('siswa_id', null))
                    ->required(),

                Select::make('siswa_id')
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



                Select::make('subject_id')
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
                    ->reactive()
                    ->preload()                   // jangan preload sebelum rombel dipilih
                    ->required(),



                Select::make('indikatornilai_id')
                    ->relationship('indikatornilai', 'id')
                    ->options(function (Get $get): array {
                        $subjectId = $get('subject_id');
                        if (blank($subjectId)) return [];

                        return SubjectsIndikatornilai::query()
                            ->with('subject:id,nama_indikator')               // eager load relasi siswa
                            ->where('subject_id', $subjectId)
                            ->get(['id','subject_id','indikatornilai_id'])
                            ->unique('indikatornilai_id')                  // pastikan distinct per siswa
                            ->sortBy(fn ($rs) => $rs->indikatornilai?->nama_indikator)
                            ->mapWithKeys(fn ($rs) => [
                                $rs->indikatornilai_id => $rs->indikatornilai?->nama_indikator ?? null
                            ])
                            ->toArray();
                    })
                    ->disabled(fn ($get) => blank($get('subject_id')))
                    ->searchable()
                    ->reactive()
                    ->preload()                   // jangan preload sebelum rombel dipilih
                    ->required(),

                    
                Select::make('teacher_id')
                    ->relationship('teacher', 'name'),
                Select::make('semester_id')
                    ->relationship('semester', 'name'),
                TextInput::make('nilai')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rombel.id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('siswa.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('subject.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('indikatornilai.id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('teacher.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('semester.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nilai')
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
            'index' => ListRombelsPenilaians::route('/'),
            'create' => CreateRombelsPenilaian::route('/create'),
            'edit' => EditRombelsPenilaian::route('/{record}/edit'),
        ];
    }
}
