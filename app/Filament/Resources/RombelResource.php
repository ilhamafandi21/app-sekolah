<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Rombel;
use App\Models\Jurusan;
use App\Models\Subject;
use App\Models\Teacher;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\StatusRombel;
use App\Enums\TingkatKelas;
use App\Traits\TahunAjaran;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RombelResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RombelResource\RelationManagers;
use App\Filament\Resources\RombelResource\RelationManagers\RombelsSubjectsRelationManager;
use App\Filament\Resources\RombelResource\RelationManagers\RombelsSubjectsTeacherRelationManager;
use App\Models\RombelsSubjects;
use App\Models\RombelsSubjectsTeacher;
use Filament\Tables\Actions\ActionGroup;

class RombelResource extends Resource
{
    protected static ?string $model = Rombel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Rombel';
    
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = -6;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('jurusan:id,nama,kode');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Umum')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('semester_id')
                                    ->label('Semester')
                                    ->relationship('semester', 'name')
                                    ->getOptionLabelFromRecordUsing(fn ($record) =>
                                        $record->name . ' - ' . optional($record->tahun_ajaran)->thn_ajaran
                                    )
                                    ->reactive()
                                    ->required(),

                                Forms\Components\Select::make('tingkat_id')
                                    ->label('Tingkat Kelas')
                                    ->options(TingkatKelas::options())
                                    ->reactive()
                                    ->required(),

                                Forms\Components\Select::make('jurusan_id')
                                    ->label('Jurusan')
                                    ->relationship('jurusan', 'nama')
                                    ->reactive()
                                    ->required(),

                                Forms\Components\TextInput::make('divisi')
                                    ->label('Divisi')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options(StatusRombel::options())
                                    ->default(StatusRombel::NONAKTIF)
                                    ->required(),

                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Rombel')
                                    ->hidden(fn (string $context) => $context === 'create')
                                    ->unique('rombels', 'name', ignoreRecord: true)
                                    ->helperText('Contoh: 2025/X/1 atau 2025/XI/IPA/1')
                                    ->validationMessages([
                                        'unique' => ':Attribute sudah digunakan.',
                                    ])
                                    ->columnSpan(2)
                                    ->required(),
                            ]),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Biaya')
                    ->schema([
                        Forms\Components\Select::make('rombel_biayas')
                            ->label('Biaya Terkait')
                            ->multiple()
                            ->relationship('biayas', 'name')
                            ->preload()
                            ->columnSpanFull(),
                        ])
                        ->columns(1),

                 Forms\Components\Select::make('subject')
                        ->label('Mata Pelajaran')
                        ->relationship('rombels_subjects', 'name')
                        ->multiple()
                        ->preload(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->badge()
                    ->color(fn ($record) => $record->status ? 'success' : 'danger')
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester.name')
                    ->description(fn ($record) => $record->semester?->tahun_ajaran?->thn_ajaran)
                    ->badge()
                    ->color(fn ($record) => $record->status ? 'success' : 'danger')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('status'),

                Tables\Columns\TextColumn::make('info_rinci')
                    ->label('Info Rombel')
                    ->getStateUsing(fn(Rombel $record) => 
                        $record->tingkat_id . ' ' . 
                        $record->jurusan?->nama . ' ' .
                        $record->divisi
                    )
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tingkat_id')
                    ->label('Tingkat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jurusan.nama')
                    ->description(fn(Rombel $record)=>"Kode jurusan:".' ' .$record->jurusan->kode)
                    ->sortable(),
                Tables\Columns\TextColumn::make('divisi')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Ket')
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
                //
            ])
            ->actions([
                ActionGroup::make([
                     Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                ]),
               
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

     public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() < 10 ? 'success' : 'primary';
    }

    public static function getRelations(): array
    {
        return [
            RombelsSubjectsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRombels::route('/'),
            'create' => Pages\CreateRombel::route('/create'),
            'edit' => Pages\EditRombel::route('/{record}/edit'),
        ];
    }
}
