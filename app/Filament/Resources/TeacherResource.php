<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\TeacherResource\Pages\ListTeachers;
use App\Filament\Resources\TeacherResource\Pages\CreateTeacher;
use App\Filament\Resources\TeacherResource\Pages\EditTeacher;
use App\Filament\Resources\TeacherResource\RelationManagers\SubjectsRelationManager;
use Filament\Forms;
use Filament\Tables;
use App\Models\Teacher;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\TeacherResource\Pages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TeacherResource\RelationManagers\UserRelationManager;
use Filament\Tables\Filters\TrashedFilter;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';
    protected static string | \UnitEnum | null $navigationGroup = 'Siswa dan Guru';
    protected static ?string $navigationLabel = 'Daftar Guru';


        public static function form(Schema $schema): Schema
        {
            return $schema->components([
                    Section::make([
                        Fieldset::make('Data Pribadi')->schema([

                                TextInput::make('nip')
                                    ->label('NIP')
                                    ->default(fn () => Teacher::generateNip())
                                    ->placeholder('Masukkan NIP guru...')
                                    ->readOnly()
                                    ->numeric()
                                    ->unique(Teacher::class, 'nip', ignoreRecord: true)
                                    ->maxLength(20)
                                    ->validationMessages([
                                        'unique' => 'NIP sudah terdaftar. Silakan gunakan NIP lain.'
                                    ])
                                    ->helperText('NIP harus unik dan tidak boleh kosong.')
                                    ->columnSpanFull()
                                    ->required(),
                                TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->placeholder('Masukkan nama lengkap guru...')
                                    ->required(),
                                DatePicker::make('tgl_lahir')
                                    ->label('Tanggal Lahir')
                                    ->native()
                                    ->required(),
                                TextInput::make('kota_lahir')
                                    ->label('Tempat Lahir')
                                    ->required(),
                                TextInput::make('pendidikan')
                                    ->label('Pendidikan Terakhir')
                                    ->required(),
                            ]),
                            Textarea::make('alamat')
                                ->label('Alamat')
                                ->placeholder('Alamat lengkap...')
                                ->required(),
                            FileUpload::make('foto')
                                ->label('Foto Profil')
                                ->image()
                                ->directory('img_teacher')
                                ->imageEditor()
                                ->imagePreviewHeight('80')
                                ->helperText('Upload foto terbaru, rasio 1:1 (square) untuk hasil terbaik.'),

                    ])
                    ->columnSpanFull(),

                    Section::make([
                        Fieldset::make('Akun Pengguna')->schema([

                                TextInput::make('user.email')
                                    ->label('Email')
                                    ->email()
                                    ->required(),
                                TextInput::make('user.password')
                                    ->password()
                                    ->label('Password')
                                    ->default('password')
                                    ->revealable()
                                    ->helperText('Default "password". Ganti jika perlu.')
                                    ->nullable()

                        ]),
                    ])
                    ->columnSpanFull()
                    ->visible(fn (string $operation) => $operation !== 'edit'),

                    Section::make([
                        Fieldset::make('Pengajaran')->schema([
                            Select::make('subject')
                                ->label('Mata Pelajaran')
                                ->relationship('subjects', 'name')
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->placeholder('Pilih mata pelajaran'),
                        ]),
                    ])->columnSpanFull(),

            ]);
        }



        public static function table(Table $table): Table
        {
            return $table
                ->defaultSort('name', 'asc')
                ->columns([
                     TextColumn::make('nip')
                        ->label('NIP')
                        ->sortable()
                        ->searchable(),
                    ImageColumn::make('foto')
                        ->label('Foto')
                        ->height(48)
                        ->width(48),
                    TextColumn::make('name')
                        ->label('Nama')
                        ->weight('bold')
                        ->searchable(),
                    TextColumn::make('user.email')
                        ->label('Email')
                        ->icon('heroicon-o-at-symbol')
                        ->searchable(),
                    TextColumn::make('tgl_lahir')
                        ->label('Tgl Lahir')
                        ->date()
                        ->sortable(),
                    TextColumn::make('kota_lahir')
                        ->label('Kota Lahir')
                        ->searchable(),
                    TextColumn::make('pendidikan')
                        ->label('Pendidikan')
                        ->badge()
                        ->color('primary')
                        ->searchable(),
                    TextColumn::make('subjects.name')
                        ->label('Mapel')
                        ->separator(',')
                        ->limit(3),
                    TextColumn::make('alamat')
                        ->label('Alamat')
                        ->limit(15)
                        ->searchable(),
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
                    // Tambah filter aktif/nonaktif kalau ada
                    // TrashedFilter::make(),
                ])
                ->recordActions([
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
            UserRelationManager::class,
            SubjectsRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 10 ? 'primary' : 'primary';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeachers::route('/'),
            'create' => CreateTeacher::route('/create'),
            'edit' => EditTeacher::route('/{record}/edit'),
        ];
    }
}
