<?php

namespace App\Filament\Resources;

use DateTime;
use Carbon\Carbon;
use Filament\Forms;
use App\Enums\Agama;
use Filament\Tables;
use App\Models\Siswa;
use Pages\ViewAction;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\OrtuSiswa;
use App\Enums\StatusSiswa;
use Filament\Tables\Table;
use App\Enums\JenisKelamin;
use App\Enums\PendTerakhir;
use App\Traits\GeneratesNis;
use Filament\Facades\Filament;
use App\Enums\StatusPendaftaran;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\SiswaResource\Pages;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Models\TahunAjaran as ModelsTahunAjaran;
use App\Traits\Tahun;
use App\Models\TahunAjaran;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Siswa';

    public static function form(Form $form): Form
    {
       return $form
    ->schema([
        Forms\Components\Fieldset::make('Data Diri')
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required(),
                Forms\Components\TextInput::make('tempat_lahir')
                    ->label('Kota Lahir')
                    ->required(),
                Forms\Components\DatePicker::make('ttl')
                    ->label('Tanggal Lahir')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            try {
                                $set('password', Carbon::parse($state)->format('Ymd'));
                            } catch (\Exception $e) {
                                $set('password', null);
                            }
                        }
                    })
                    ->required(),
                Forms\Components\Select::make('jenis_kelamin')
                    ->options(JenisKelamin::options())
                    ->required(),
                Forms\Components\Textarea::make('alamat')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\Select::make('agama')
                    ->options(Agama::options()),

                Forms\Components\TextInput::make('nis')
                    ->default(function () {
                        return (new class {
                            use GeneratesNis;
                        })->generateNis();
                    })
                    ->readOnly()
                    ->reactive()
                    ->required(),

                Forms\Components\TextInput::make('nisn')->required(),
                Forms\Components\TextInput::make('asal_sekolah')->required(),
            ])
            ->columns(2),

        Forms\Components\Fieldset::make('Orang Tua')
            ->relationship('ortus')
            ->schema([
                Forms\Components\TextInput::make('nama_ibu')->required(),
                Forms\Components\TextInput::make('nama_ayah')
                    ->label('Nama Ayah')
                    ->required(),
                Forms\Components\TextInput::make('pekerjaan_ibu')
                    ->label('Pekerjaan Ibu')
                    ->required(),
                Forms\Components\TextInput::make('pekerjaan_ayah')
                    ->label('Pekerjaan Ayah')
                    ->required(),
                Forms\Components\Select::make('pend_terakhir_ibu')
                    ->label('Pendidikan Terakhir Ibu')
                    ->options(PendTerakhir::options())
                    ->default(PendTerakhir::SD)
                    ->required(),
                Forms\Components\Select::make('pend_terakhir_ayah')
                    ->label('Pendidikan Terakhir Ayah')
                    ->options(PendTerakhir::options())
                    ->default(PendTerakhir::SD)
                    ->required(),
                Forms\Components\TextInput::make('phone')
                    ->label('No Telepon Ayah/Ibu')
                    ->required(),
                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->default(fn (Get $get) => $get('nis'))
                    ->required(),
            ])
            ->columns(2),

        Forms\Components\Fieldset::make('Dokumen Tambahan')
            ->schema([
                Forms\Components\Repeater::make('documents')
                    ->label('Dokumen Tambahan')
                    ->relationship('documents')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Dokumen')
                            ->required(),
                        Forms\Components\FileUpload::make('image')
                            ->label('File')
                            ->directory('dokumen-siswa')
                            ->maxSize(10240),
                    ])
                    ->nullable()
                    ->addActionLabel('Tambah Dokumen')
                    ->collapsible()
                    ->grid(1)
                    ->columnSpan('full')
                    ->default([]),
            ]),

        Forms\Components\Fieldset::make('Status & Akun')
            ->schema([
                Forms\Components\Select::make('status_pendaftaran')
                    ->options(StatusPendaftaran::options())
                    ->default(StatusPendaftaran::PENDING)
                    ->reactive()
                    ->required(),

                Forms\Components\Select::make('status_siswa')
                    ->options(function (callable $get, callable $set) {
                        $statusPendaftaran = $get('status_pendaftaran');

                        if ($statusPendaftaran === StatusPendaftaran::PENDING) {
                            $set('status_siswa', StatusSiswa::NONAKTIF);
                        } elseif ($statusPendaftaran === StatusPendaftaran::APPROVED) {
                            $set('status_siswa', StatusSiswa::AKTIF);
                        } elseif ($statusPendaftaran === StatusPendaftaran::REJECTED) {
                            $set('status_siswa', StatusSiswa::NONAKTIF);
                        }

                        return StatusSiswa::options();
                    })
                    ->default(StatusSiswa::NONAKTIF)
                    ->required(),

                Forms\Components\Select::make('tahun_ajaran')
                // Forms\Components\CheckboxList::make('subjects')-> hapus multiple dan preload gunakan CheckboxList 
                    ->label('Siswa')
                    ->multiple()
                    ->relationship('tahuns', 'tahun') // Tetap gunakan ini agar pivot auto disimpan
                    ->options(TahunAjaran::all()->pluck('tahun', 'id')),
                Forms\Components\DatePicker::make('waktu_siswa_aktif')
                    ->default(null),

                Forms\Components\TextInput::make('password')
                    ->required()
                    ->readOnly()
                    ->dehydrated()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state)),

                Forms\Components\TextInput::make('role')
                    ->default('siswa')
                    ->readOnly()
                    ->required()
                    ->dehydrated(),

                Forms\Components\TextInput::make('user_id')
                    ->default(Filament::auth()->user()->id)
                    ->readOnly()
                    ->required()
                    ->dehydrated(),
            ])
            ->columns(2),
    ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asal_sekolah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_pendaftaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status_siswa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahuns.tahun')
                    ->label('Tahun Ajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu_siswa_aktif')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
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
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
            'view' => Pages\SiswaDetail::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Data Pribadi')
                    ->icon('heroicon-o-identification') // kartu identitas
                    ->columns(2)
                    ->schema([
                        TextEntry::make('nama'),
                        TextEntry::make('tempat_lahir'),
                        TextEntry::make('ttl'),
                        TextEntry::make('jenis_kelamin'),
                        TextEntry::make('alamat'),
                        TextEntry::make('email'),
                        TextEntry::make('rombels.tingkat')
                            ->label('Tingkat Kelas'),
                        TextEntry::make('rombels.jurusan.nama')
                            ->label('Jurusan'),
                        TextEntry::make('rombels.divisi')
                            ->label('Divisi'),
                    ]),

                Section::make('Data Orang Tua')
                    ->icon('heroicon-o-users') // orang tua (dua orang)
                    ->columns(2)
                    ->schema([
                        TextEntry::make('ortus.nama_ibu'),
                        TextEntry::make('ortus.pekerjaan_ibu'),
                        TextEntry::make('ortus.nama_ayah'),
                        TextEntry::make('ortus.pekerjaan_ayah'),
                    ]),

                Section::make('Pendidikan & Status')
                    ->icon('heroicon-o-academic-cap') // simbol pendidikan
                    ->columns(2)
                    ->schema([
                        TextEntry::make('asal_sekolah'),
                        TextEntry::make('status_pendaftaran'),
                        TextEntry::make('status_siswa'),
                    ]),

                Section::make('Waktu')
                    ->icon('heroicon-o-calendar-days') // tanggal
                    ->columns(2)
                    ->schema([
                        TextEntry::make('tahuns.tahun')
                            ->label('Tahun Ajaran'),
                        TextEntry::make('waktu_siswa_aktif')->dateTime('d M Y H:i'),
                    ]),
     
                Section::make('Dokumen Tambahan')
                    ->icon('heroicon-o-folder-arrow-down') // simbol dokumen
                    ->schema([
                        // ImageEntry::make('documents.image')
                        //     ->label('Dokumen')
                        //     ->hiddenLabel()
                        //     ->circular(),
                        
                        RepeatableEntry::make('documents')
                            ->schema([
                                ImageEntry::make('image')
                                ->label(''),
                                TextEntry::make('image')
                                    ->label('')
                                    ->url(fn($record) => Storage::url($record->image))
                                    ->openUrlInNewTab()
                                    ->columnSpan(2),
                            ])
                            ->columns(2),
                        

                        // ViewEntry::make('documents')
                        //     ->label('Klik untuk lihat file')
                        //     ->view('filament.infolists.components.dokumen-link'),
                    ]),

                Section::make('Informasi Sistem')
                    ->icon('heroicon-o-cog-6-tooth') // ikon pengaturan/sistem
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextEntry::make('role'),
                        TextEntry::make('user.name')->label('User Input'),
                        TextEntry::make('created_at')->dateTime('d M Y H:i'),
                        TextEntry::make('updated_at')->dateTime('d M Y H:i'),
                        TextEntry::make('deleted_at')
                            ->dateTime('d M Y H:i')
                            ->hidden(fn ($record) => $record->deleted_at === null),
                    ]),

            ]);
    }
}
