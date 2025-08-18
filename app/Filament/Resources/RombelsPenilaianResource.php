<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RombelsSiswa;
use App\Models\RombelsPenilaian;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RombelsPenilaianResource\Pages;
use App\Filament\Resources\RombelsPenilaianResource\RelationManagers;

class RombelsPenilaianResource extends Resource
{
    protected static ?string $model = RombelsPenilaian::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Forms\Components\Select::make('rombel_id')
                    ->label('Rombel')
                    ->relationship('rombel', 'id')   // tampilkan kode rombel (bukan id)
                    ->reactive()                           // atau ->reactive() pada versi lama
                    ->afterStateUpdated(fn ($set) => $set('siswa_id', null))
                    ->required(),

                Forms\Components\Select::make('siswa_id')
                    ->label('Siswa')
                    ->options(function (Get $get) {
        $rombelId = $get('rombel_id');
        if (blank($rombelId)) return [];

        // Cara 1 — join pivot -> siswas (paling efisien)
                return RombelsSiswa::query()
                    ->where('rombel_id', $rombelId)
                    ->join('siswas as s', 's.id', '=', 'rombels_siswas.siswa_id') // ganti nama tabel pivot jika beda
                    ->orderBy('s.name')                                          // sesuaikan 'name' -> 'nama' kalau perlu
                    ->distinct()
                    ->pluck('s.name', 'rombels_siswas.siswa_id')
                    ->toArray();

                // Cara 2 — in query (alternatif):
                // $ids = RombelsSiswa::where('rombel_id', $rombelId)->pluck('siswa_id')->unique();
                // return Siswa::whereIn('id', $ids)->orderBy('name')->pluck('name', 'id')->toArray();
            })
                    ->disabled(fn ($get) => blank($get('rombel_id')))
                    ->searchable()
                    ->preload()                   // jangan preload sebelum rombel dipilih
                    ->required(),

                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name'),
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
