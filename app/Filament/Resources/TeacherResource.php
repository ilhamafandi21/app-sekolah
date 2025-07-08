<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Teacher;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TeacherResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Filament\Resources\TeacherResource\RelationManagers\UserRelationManager;
use Filament\Facades\Filament;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Guru';
    protected static ?string $navigationLabel = 'Guru';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Sembunyikan user_id dari pengguna
            Forms\Components\TextInput::make('user_id')
                ->required()
                ->dehydrated(false)
                ->hidden(),

            Forms\Components\Fieldset::make('Data Pribadi')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Lengkap')
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
                    ->required(),

                Forms\Components\FileUpload::make('foto')
                    ->label('Foto Profil')
                    ->image()
                    ->directory('img_teacher'),
            ]),

            Forms\Components\Fieldset::make('Akun Pengguna')->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('user.email')
                        ->label('Email')
                        ->email()
                        ->required(),
                    Forms\Components\TextInput::make('user.password')
                        ->password()
                        ->label('Password')
                        ->default('password')
                        ->revealable()
                        ->helperText('ini secara default "password" silahkan ganti jika butuh penyesuaian!')
                        ->nullable()
                ]),
            ])
            ->visible(fn (string $operation) => $operation !== 'edit'),

            Forms\Components\Fieldset::make('Pengajaran')->schema([
                Forms\Components\Select::make('subject')
                    ->label('Mata Pelajaran')
                    ->relationship('subjects', 'name')
                    ->multiple()
                    ->preload(),
            ]),
        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tgl_lahir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kota_lahir')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->limit(10)
                    ->searchable(),
                Tables\Columns\TextColumn::make('pendidikan')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('foto')
                    ->searchable(),
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
            UserRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 10 ? 'success' : 'primary';
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
