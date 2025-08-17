<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\RelationManagers\SubjectsRelationManager;
use Filament\Forms;
use Filament\Tables;
use App\Models\Teacher;
use Filament\Forms\Form;
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

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?string $navigationLabel = 'Guru';
    protected static ?int $navigationSort = -10;


        public static function form(Form $form): Form
        {
            return $form->schema([
                    Forms\Components\Section::make([
                        Forms\Components\Fieldset::make('Data Pribadi')->schema([
                            Forms\Components\Grid::make()->schema([
                                 Forms\Components\TextInput::make('nip')
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
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->placeholder('Masukkan nama lengkap guru...')
                                    ->required(),
                                Forms\Components\DatePicker::make('tgl_lahir')
                                    ->label('Tanggal Lahir')
                                    ->native(false)
                                    ->required(),
                                Forms\Components\TextInput::make('kota_lahir')
                                    ->label('Tempat Lahir')
                                    ->required(),
                                Forms\Components\TextInput::make('pendidikan')
                                    ->label('Pendidikan Terakhir')
                                    ->required(),
                            ]),
                            Forms\Components\Textarea::make('alamat')
                                ->label('Alamat')
                                ->placeholder('Alamat lengkap...')
                                ->required(),
                            Forms\Components\FileUpload::make('foto')
                                ->label('Foto Profil')
                                ->image()
                                ->directory('img_teacher')
                                ->imageEditor()
                                ->imagePreviewHeight('80')
                                ->helperText('Upload foto terbaru, rasio 1:1 (square) untuk hasil terbaik.'),
                        ])
                    ]),

                    Forms\Components\Section::make([
                        Forms\Components\Fieldset::make('Akun Pengguna')->schema([
                            Forms\Components\Grid::make()->schema([
                                Forms\Components\TextInput::make('user.email')
                                    ->label('Email')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('user.password')
                                    ->password()
                                    ->label('Password')
                                    ->default('password')
                                    ->revealable()
                                    ->helperText('Default "password". Ganti jika perlu.')
                                    ->nullable()
                            ]),
                        ]),
                    ])->visible(fn (string $operation) => $operation !== 'edit'),

                    Forms\Components\Section::make([
                        Forms\Components\Fieldset::make('Pengajaran')->schema([
                            Forms\Components\Select::make('subject')
                                ->label('Mata Pelajaran')
                                ->relationship('subjects', 'name')
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->placeholder('Pilih mata pelajaran'),
                        ]),
                    ]),
               
            ]);
        }



        public static function table(Table $table): Table
        {
            return $table
                ->defaultSort('name', 'asc')
                ->columns([
                     Tables\Columns\TextColumn::make('nip')
                        ->label('NIP')
                        ->sortable()
                        ->searchable(),
                    Tables\Columns\ImageColumn::make('foto')
                        ->label('Foto')
                        ->height(48)
                        ->width(48),
                    Tables\Columns\TextColumn::make('name')
                        ->label('Nama')
                        ->weight('bold')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('user.email')
                        ->label('Email')
                        ->icon('heroicon-o-at-symbol')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('tgl_lahir')
                        ->label('Tgl Lahir')
                        ->date()
                        ->sortable(),
                    Tables\Columns\TextColumn::make('kota_lahir')
                        ->label('Kota Lahir')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('pendidikan')
                        ->label('Pendidikan')
                        ->badge()
                        ->color('primary')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('subjects.name')
                        ->label('Mapel')
                        ->separator(',')
                        ->limit(3),
                    Tables\Columns\TextColumn::make('alamat')
                        ->label('Alamat')
                        ->limit(15)
                        ->searchable(),
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
                    // Tambah filter aktif/nonaktif kalau ada
                    // TrashedFilter::make(),
                ])
                ->actions([
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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
