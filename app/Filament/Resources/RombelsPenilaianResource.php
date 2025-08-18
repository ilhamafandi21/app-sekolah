<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\RombelsPenilaian;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RombelsPenilaianResource\Pages;
use App\Filament\Resources\RombelsPenilaianResource\RelationManagers;

class RombelsPenilaianResource extends Resource
{
    protected static ?string $model = RombelsPenilaian::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
               Select::make('rombel_id')
    ->label('Rombel')
    ->relationship(
        name: 'rombel',
        titleAttribute: 'kode',
        modifyQueryUsing: fn (Builder $q) =>
            $q->whereExists(function ($sub) {
                $sub->from('rombel_siswa as rs')           // <-- pivot table
                    ->selectRaw('1')
                    ->whereColumn('rs.rombel_id', 'rombels.id');
            })
    )
    ->live()
    ->afterStateUpdated(fn (Set $set) => $set('siswa_id', null))
    ->searchable()
    ->preload()
    ->required(),


                Forms\Components\Select::make('siswa_id')
                    ->label('Siswa')
                    ->relationship(
                        name: 'siswa',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query, $get) =>
                            $query->when($get('rombel_id'), fn ($q) =>
                                $q->where('rombel_id', $get('rombel_id'))
                                
                            )
                    )
                    ->disabled(fn ($get) => blank($get('rombel_id')))
                    ->searchable()
                    ->preload()                   // jangan preload sebelum rombel dipilih
                    ->required(),

                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'name'),
                Forms\Components\Select::make('indikatornilai_id')
                    ->relationship('indikatornilai', 'id'),
                Forms\Components\Select::make('teacher_id')
                    ->relationship('teacher', 'name'),
                Forms\Components\Select::make('semester_id')
                    ->relationship('semester', 'name'),
                Forms\Components\TextInput::make('nilai')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rombel.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('siswa.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('indikatornilai.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teacher.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('semester.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nilai')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRombelsPenilaians::route('/'),
            'create' => Pages\CreateRombelsPenilaian::route('/create'),
            'edit' => Pages\EditRombelsPenilaian::route('/{record}/edit'),
        ];
    }
}
