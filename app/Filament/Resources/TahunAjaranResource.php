<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TahunAjaranResource\Pages\ListTahunAjarans;
use App\Filament\Resources\TahunAjaranResource\Pages\CreateTahunAjaran;
use App\Filament\Resources\TahunAjaranResource\Pages\EditTahunAjaran;
use App\Filament\Resources\TahunAjaranResource\Pages;
use App\Filament\Resources\TahunAjaranResource\RelationManagers;
use App\Models\TahunAjaran;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TahunAjaranResource extends Resource
{
    protected static ?string $model = TahunAjaran::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar';
    protected static string | \UnitEnum | null $navigationGroup = 'Master Data Akademik';
    protected static ?string $navigationLabel = 'Tahun Ajaran';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('thn_ajaran')
                    ->afterStateUpdated(fn ($state, callable $set) =>
                        $set('thn_ajaran', preg_replace('/\s+/', '', $state))
                    )
                    ->rule('regex:/^\d{4}-\d{4}$/')
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'regex' => 'Format tahun ajaran harus seperti Contoh: 2000-2001',
                        'unique' => 'Tahun Ajaran ini sudah ada'
                    ])
                    ->required(),

                Textarea::make('keterangan')
                    ->default('-')
                    ->required(),

                Toggle::make('status')
                    ->label('active')
                    ->required()
                    ->default(true),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('thn_ajaran')
                    ->searchable(),
                ToggleColumn::make('status')
                    ->searchable(),
                TextColumn::make('keterangan')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make()
                        ->label('Hapus')
                        ->modalAlignment()
                        ->modalSubmitActionLabel('hapus'),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus'),
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
            'index' => ListTahunAjarans::route('/'),
            'create' => CreateTahunAjaran::route('/create'),
            'edit' => EditTahunAjaran::route('/{record}/edit'),
        ];
    }
}
