<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Siswa;
use Filament\Forms\Form;
use App\Models\Pembayaran;
use Filament\Tables\Table;
use Filament\Facades\Filament;
use App\Enums\StatusPembayaran;
use App\Models\JenisPembayaran;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PembayaranResource\Pages;
use App\Filament\Resources\PembayaranResource\RelationManagers;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationLabel = 'Pembayaran';
    
    protected static ?string $navigationGroup = 'Tata Usaha';

    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()->with('siswa:id,nama');
    // }

    public function getTitle(): string | Htmlable
    {
        return __('Custom Page Title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('siswa_id') // ✅ Nama kolom di database
                    ->label('Nama Siswa')
                    ->options(function () {
                        return Siswa::orderBy('nama')->get()->mapWithKeys(function ($siswa) {
                            // dd() di sini akan mencetak satu-per-satu
                            return [
                                $siswa->id => "{$siswa->nama} ({$siswa->nis})",
                            ];
                        });
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('user_id')
                    ->default(Filament::auth()->user()->id)
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                Forms\Components\Select::make('jenis_pembayaran_id')
                    ->options(JenisPembayaran::all()->pluck('title', 'id'))
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options(StatusPembayaran::options())
                    ->default(StatusPembayaran::PENDING)
                    ->preload(),
                Forms\Components\TextInput::make('keterangan')
                    ->default('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_pembayaran.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('keterangan'),
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
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }

  
}
