<?php

namespace App\Filament\Resources;

use App\Enums\Agama;
use App\Enums\JenisKelamin;
use App\Enums\StatusSiswa;
use Filament\Forms;
use Filament\Tables;
use App\Models\Siswa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use function Laravel\Prompts\password;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;

use App\Filament\Resources\SiswaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Traits\TahunLulus;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?string $navigationLabel = 'Murid';
    protected static ?int $navigationSort = -9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nis')
                    ->label('NIS')
                    ->default(fn () => Siswa::generateNis())
                    ->placeholder('Masukkan NIS siswa...')
                    ->readOnly()
                    ->numeric()
                    ->unique(Siswa::class, 'nis', ignoreRecord: true)
                    ->maxLength(20)
                    ->validationMessages([
                        'unique' => 'NIS sudah terdaftar. Silakan gunakan NIS lain.'
                    ])
                    ->helperText('NIS harus unik dan tidak boleh kosong.')
                    ->columnSpanFull()
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('tempat_lahir'),
                Forms\Components\DatePicker::make('tanggal_lahir'),
                Forms\Components\Textarea::make('alamat')
                    ->columnSpanFull(),
                Forms\Components\Select::make('agama')
                    ->options(Agama::options())
                    ->default(Agama::ISLAM),
                Forms\Components\Select::make('jenis_kelamin')
                    ->options(JenisKelamin::options())
                    ->default(JenisKelamin::LAKI_LAKI),
                Forms\Components\TextInput::make('asal_sekolah'),
                Forms\Components\Select::make('tahun_lulus')
                    ->label('Tahun Lulus')
                    ->options(
                        TahunLulus::tahun_lulus()
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Repeater::make('documents')
                    ->addActionLabel('Tambah Dokumen')
                    ->relationship('documents')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->nullable(),
                        Forms\Components\FileUpload::make('files')
                            ->directory('dokumen-siswa')    
                            ->nullable(),
                        Forms\Components\Textarea::make('ket')
                            ->label('keterangan')
                            ->default('-')    
                            ->nullable(),
                    ]),

                Forms\Components\Select::make('status')
                    ->options(StatusSiswa::options())
                    ->default(StatusSiswa::AKTIF),
                Forms\Components\TextInput::make('user_id')
                    ->dehydrated(false)
                    ->hidden()
                    ->numeric(),

                Forms\Components\TextInput::make('user.email')
                    ->visible(fn (string $context) => $context === 'create')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('user.password')
                    ->visible(fn (string $context) => $context === 'create')
                    ->default('password')
                    ->revealable()
                    ->password(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tempat_lahir')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asal_sekolah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_lulus')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('documents.files')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_id')
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
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),

                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'aktif')->count();
    }

     public static function getNavigationBadgeColor(): ?string
    {
       // Hitung jumlah siswa aktif
        $aktif = static::getModel()::where('status', 'aktif')->count();

        // Jika ada yang aktif, tampilkan 'success', jika tidak, tampilkan 'primary'
        return $aktif > 0 ? 'success' : 'primary';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}
