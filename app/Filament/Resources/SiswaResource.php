<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\SiswaResource\Pages\ListSiswas;
use App\Filament\Resources\SiswaResource\Pages\CreateSiswa;
use App\Filament\Resources\SiswaResource\Pages\EditSiswa;
use App\Enums\Agama;
use App\Enums\JenisKelamin;
use App\Enums\StatusSiswa;
use Filament\Forms;
use Filament\Tables;
use App\Models\Siswa;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Siswa';
    protected static ?int $navigationSort = -9;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nis')
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
                TextInput::make('name')
                    ->required(),
                TextInput::make('tempat_lahir'),
                DatePicker::make('tanggal_lahir'),
                Textarea::make('alamat')
                    ->columnSpanFull(),
                Select::make('agama')
                    ->options(Agama::options())
                    ->default(Agama::ISLAM),
                Select::make('jenis_kelamin')
                    ->options(JenisKelamin::options())
                    ->default(JenisKelamin::LAKI_LAKI),
                TextInput::make('asal_sekolah'),
                Select::make('tahun_lulus')
                    ->label('Tahun Lulus')
                    ->options(
                        TahunLulus::tahun_lulus()
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Repeater::make('documents')
                    ->addActionLabel('Tambah Dokumen')
                    ->relationship('documents')
                    ->schema([
                        TextInput::make('name')
                            ->nullable(),
                        FileUpload::make('files')
                            ->directory('dokumen-siswa')    
                            ->nullable(),
                        Textarea::make('ket')
                            ->label('keterangan')
                            ->default('-')    
                            ->nullable(),
                    ]),

                Select::make('status')
                    ->options(StatusSiswa::options())
                    ->default(StatusSiswa::AKTIF),
                TextInput::make('user_id')
                    ->dehydrated(false)
                    ->hidden()
                    ->numeric(),

                TextInput::make('user.email')
                    ->visible(fn (string $context) => $context === 'create')
                    ->email()
                    ->required(),
                TextInput::make('user.password')
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
                TextColumn::make('nis')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('tempat_lahir')
                    ->searchable(),
                TextColumn::make('tanggal_lahir')
                    ->date()
                    ->sortable(),
                TextColumn::make('agama')
                    ->searchable(),
                TextColumn::make('jenis_kelamin')
                    ->searchable(),
                TextColumn::make('asal_sekolah')
                    ->searchable(),
                TextColumn::make('tahun_lulus')
                    ->searchable(),
                ImageColumn::make('documents.files')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('user_id')
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
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make()
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),

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
            'index' => ListSiswas::route('/'),
            'create' => CreateSiswa::route('/create'),
            'edit' => EditSiswa::route('/{record}/edit'),
        ];
    }
}
