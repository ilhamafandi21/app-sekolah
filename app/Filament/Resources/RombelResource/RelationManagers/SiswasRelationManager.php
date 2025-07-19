<?php

namespace App\Filament\Resources\RombelResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Siswa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Infolists\Components\Actions\Action as ActionsAction;

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
                    ->modalHeading('')
                    ->slideOver()
                    ->modalWidth('full')
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
        Section::make('Data Pribadi')
            ->schema([
                TextEntry::make('name')->label('Nama Lengkap'),
                TextEntry::make('tempat_lahir')->label('Tempat Lahir'),
                TextEntry::make('tanggal_lahir')->label('Tanggal Lahir'),
                TextEntry::make('jenis_kelamin')->label('Jenis Kelamin'),
                // TextEntry::make('agama')->label('Agama'),
                // TextEntry::make('alamat')->label('Alamat Lengkap'),
            ])
            ->columns(4),

        Section::make('Data Akademik')
            ->schema([
                TextEntry::make('kelas')
                    ->label('Kelas')
                    ->getStateUsing(function ($record) {
                        return $record->rombels
                            ->map(fn ($sr) => $sr->tingkat_id . '-' . $sr->jurusan?->nama . '-' . $sr->divisi)
                            ->filter()
                            ->implode(', ') ?: '-';
                    }),
                // TextEntry::make('asal_sekolah')->label('Asal Sekolah'),
                // TextEntry::make('tahun_lulus')->label('Tahun Lulus'),
                TextEntry::make('status')->label('Status Siswa'),
            ])
            ->columns(2),

        Section::make('Data Nilai')
            ->schema([
                TextEntry::make('rombelsSiswa.rombel.rombels_subjects.name')
                    ->label('Mata Pelajaran'),
                
            ])
            ->columns(2)
    
            
        ]);
    }
}
