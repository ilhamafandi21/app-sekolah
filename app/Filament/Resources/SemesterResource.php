<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Semester;
use Filament\Forms\Form;
use App\Enums\StatusEnum;
use Filament\Tables\Table;
use App\Enums\SemesterEnum;
use Illuminate\Validation\Rule;
use Filament\Resources\Resource;
use function Pest\Laravel\options;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SemesterResource\Pages;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SemesterResource\RelationManagers;

class SemesterResource extends Resource
{
    protected static ?string $model = Semester::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Semester';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tahun_ajaran_id')
                    ->relationship('tahun_ajaran', 'thn_ajaran')
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('name')
                    ->label('Semester')
                    ->options(SemesterEnum::options())
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options(StatusEnum::options())
                    ->default(StatusEnum::AKTIF)
                    ->required(),
                Forms\Components\TextInput::make('keterangan')
                    ->default('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->formatStateUsing(fn ($state) => $state ? SemesterEnum::from($state)->label() : '-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_ajaran.thn_ajaran')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSemesters::route('/'),
            'create' => Pages\CreateSemester::route('/create'),
            'edit' => Pages\EditSemester::route('/{record}/edit'),
        ];
    }
}
