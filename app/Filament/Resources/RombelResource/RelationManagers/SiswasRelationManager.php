<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SiswasRelationManager extends RelationManager
{
    protected static string $relationship = 'siswas';
    protected static ?string $title = 'Data Siswa';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No.')
                    ->getStateUsing(function ($record, $livewire, $column) {
                        return ($livewire->getTableRecords()->search(fn ($r) => $r->getKey() === $record->getKey()) + 1);
                }),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                ->getStateUsing(function ($record) {
                        return $record->rombels
                            ->map(fn ($sr) => $sr->tingkat_id. '-' .Str::limit($sr->jurusan->nama, 5).'-'.$sr->divisi)
                            ->filter() // hilangkan null
                            ->implode(', ') ?: '-';
                    }),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat')
                    ->modalHeading(''),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // 
                ]),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make([
                    TextEntry::make('name'),
                    TextEntry::make('kelas')
                        ->label('Kelas')
                        ->getStateUsing(function ($record) {
                            return $record->rombels
                                ->map(fn ($sr) => $sr->tingkat_id . '-' . $sr->jurusan?->nama . '-' . $sr->divisi)
                                ->filter()
                                ->implode(', ') ?: '-';
                    }),
                    TextEntry::make('tempat_lahir'),
                    TextEntry::make('tanggal_lahir'),
                    TextEntry::make('alamat'),
                    TextEntry::make('agama'),
                    TextEntry::make('jenis_kelamin'),
                    TextEntry::make('asal_sekolah'),
                    TextEntry::make('tahun_lulus'),
                    TextEntry::make('status'),
                    TextEntry::make('user.email'),
                    TextEntry::make('user.password'),
                ])
                ->heading('Data Siswa')

            ]);
    }
}
