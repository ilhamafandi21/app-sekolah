<?php
namespace App\Filament\Resources;

use App\Models\Rombel;
use App\Models\Semester;
use App\Models\TahunAjaran;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class RombelResource extends Resource
{
    protected static ?string $model = Rombel::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Rombel';
    protected static ?string $navigationGroup = 'Akademik';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('tahun_ajaran_id')
                ->label('Tahun Ajaran')
                ->relationship('tahunAjaran', 'name')
                ->required()
                ->preload()
                ->searchable(),

            Forms\Components\Select::make('semester_id')
                ->label('Semester')
                ->relationship('semester', 'name', fn ($query, $get) =>
                    $query->where('tahun_ajaran_id', $get('tahun_ajaran_id'))
                )
                ->required()
                ->preload()
                ->searchable()
                ->reactive(),







Forms\Components\TextInput::make('name')
    ->label('Nama Rombel')
    ->required()
    ->reactive()
    ->rule(function (callable $get) {
        return function (string $attribute, $value, $fail) use ($get) {
            $tahunAjaranId = $get('tahun_ajaran_id');
            $semesterId = $get('semester_id');

            if (! $tahunAjaranId || ! $semesterId || ! $value) return;

            $exists = \App\Models\Rombel::query()
                ->where('tahun_ajaran_id', $tahunAjaranId)
                ->where('semester_id', $semesterId)
                ->where('name', $value)
                ->when(request()->route('record'), fn($q) =>
                    $q->where('id', '!=', request()->route('record'))
                )
                ->exists();

            if ($exists) {
                $fail('Nama rombel ini sudah digunakan untuk tahun ajaran dan semester tersebut.');
            }
        };
    }),


























            Forms\Components\Select::make('wali_kelas_id')
                ->label('Wali Kelas')
                ->relationship('waliKelas', 'name')
                ->preload()
                ->searchable()
                ->nullable(),

            Forms\Components\Toggle::make('is_active')
                ->label('Aktif')
                ->helperText('Centang jika rombel ini aktif.')
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('tahunAjaran.name')->label('Tahun Ajaran')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('semester.name')->label('Semester')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('name')->label('Rombel')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('waliKelas.name')->label('Wali Kelas')->searchable(),
            Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
            Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')->label('Diperbarui')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('tahun_ajaran_id')
                ->label('Tahun Ajaran')
                ->relationship('tahunAjaran', 'name'),
            Tables\Filters\SelectFilter::make('semester_id')
                ->label('Semester')
                ->relationship('semester', 'name'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => RombelResource\Pages\ListRombels::route('/'),
            'create' => RombelResource\Pages\CreateRombel::route('/create'),
            'edit' => RombelResource\Pages\EditRombel::route('/{record}/edit'),
        ];
    }
}
