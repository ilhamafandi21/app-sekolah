<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Jurusan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Traits\GenerateJurusanKode;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\JurusanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\JurusanResource\RelationManagers;
use App\Filament\Resources\JurusanResource\RelationManagers\SubjectsRelationManager;

class JurusanResource extends Resource
{
    protected static ?string $model = Jurusan::class;

    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    protected static ?string $navigationLabel = "Jurusan";

    protected static ?string $navigationGroup = "Akademik";
    protected static ?int $navigationSort = -7;

    use GenerateJurusanKode;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make("kode")
                ->default(GenerateJurusanKode::kode_jurusan())
                ->unique(
                    table: Jurusan::class,
                    column: "kode",
                    ignoreRecord: true
                )
                // ->readOnly()
                ->validationMessages([
                    "unique" =>
                        "Kode sudah maksimal, tidak bisa tambah baru lagi.",
                    "required" => "Kode wajib diisi.",
                ])
                ->required(),

            Forms\Components\TextInput::make("nama")
                ->unique(
                    table: Jurusan::class,
                    column: "nama",
                    ignoreRecord: true)
                ->validationMessages([
                    "unique" => "Jurusan dengan nama tersebut sudah ada.",
                    "required" => "Nama jurusan wajib diisi.",
                ])
                ->dehydrateStateUsing(
                    fn ($state) => strtoupper($state)
                )
                ->required(),

            Forms\Components\Select::make("subjects")
                ->label("Tambah Mapel")
                // Forms\Components\CheckboxList::make('subjects')-> hapus multiple dan preload gunakan CheckboxList
                ->multiple()
                ->relationship("subjects", "name")
                ->preload(),

            Forms\Components\Textarea::make("deskripsi")
                ->default("-")
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort("created_at", "desc")
            ->columns([
                Tables\Columns\TextColumn::make("kode")->searchable(),
                Tables\Columns\TextColumn::make("nama")
                    ->label('Jurusan')
                    ->searchable(),
                Tables\Columns\TextColumn::make("subjects.name")
                    ->label('Mapel')
                    ->limit(10)
                    ->searchable(),
                Tables\Columns\TextColumn::make("created_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make("updated_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make("deleted_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
        return [SubjectsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListJurusans::route("/"),
            "create" => Pages\CreateJurusan::route("/create"),
            "edit" => Pages\EditJurusan::route("/{record}/edit"),
        ];
    }
}
