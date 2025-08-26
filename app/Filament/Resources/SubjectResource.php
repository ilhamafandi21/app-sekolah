<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\SubjectResource\Pages\ListSubjects;
use App\Filament\Resources\SubjectResource\Pages\CreateSubject;
use App\Filament\Resources\SubjectResource\Pages\EditSubject;
use Filament\Forms;
use Filament\Tables;
use App\Models\Subject;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SubjectResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SubjectResource\RelationManagers;
use App\Filament\Resources\SubjectResource\RelationManagers\IndikatornilaisRelationManager;
use App\Traits\GenerateSubjectsKode;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Subjects';

    use GenerateSubjectsKode;

    public static function form(Schema $schema): Schema
    {
        return $schema
       
            ->components([
                TextInput::make('name')
                    ->label('Nama Mata Pelajaran')
                    ->unique(table: Subject::class, column: 'name', ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Mata pelajaran dengan nama tersebut sudah ada.',
                        'required' => 'Nama mata pelajaran wajib diisi.',
                    ])
                    ->dehydrateStateUsing(fn ($state) => strtoupper($state))
                    ->required(),

                 TextInput::make('nilai_kkm')
                    ->label('Nilai KKM')
                    ->numeric()
                    ->default(0)
                    ->nullable(),


                TextInput::make('kode')
                    ->unique(table: Subject::class, column: 'kode', ignoreRecord: true)
                    ->readOnly()
                    ->default(GenerateSubjectsKode::kode_subject())
                    ->validationMessages([
                        'unique' => 'Kode sudah maksimal, tidak bisa tambah baru lagi.',
                        'required' => 'Kode wajib diisi.',
                    ]),
                Textarea::make('deskripsi')
                    ->default('-')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
         ->defaultSort('kode', 'desc')
            ->columns([
                TextColumn::make('kode')
                    ->searchable(),
                TextColumn::make('name')
                    ->limit(15)
                    ->searchable(),
                TextColumn::make('nilai_kkm')
                    ->limit(15)
                    ->searchable(),
                TextColumn::make('jurusans.nama')
                    ->limit(15)
                    ->searchable(),
                TextColumn::make('deskripsi')
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
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
               //
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
            IndikatornilaisRelationManager::class,
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
            'index' => ListSubjects::route('/'),
            'create' => CreateSubject::route('/create'),
            'edit' => EditSubject::route('/{record}/edit'),
        ];
    }
}
