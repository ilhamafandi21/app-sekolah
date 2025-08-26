<?php

namespace App\Filament\Resources\DayResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Tables;
use App\Models\Schedull;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SchedullsRelationManager extends RelationManager
{
    protected static string $relationship = 'schedulls';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('kode'),  
                TimePicker::make('start_at'),
                TimePicker::make('end_at'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('kode'),
                TextColumn::make('start_at'),
                TextColumn::make('end_at'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (Array $data){
                        $hari = $this->getOwnerRecord()->nama_hari;
                        $startAt =  Carbon::parse($data['start_at'])->format('H:i');
                        $endAt =  Carbon::parse($data['end_at'])->format('H:i');
                        $kombinasi = $hari. '/' .$startAt. '/' .$endAt;

                        $cek = Schedull::where('kode', $kombinasi)
                                    ->where('day_id', $this->getOwnerRecord())
                                    ->where('start_at', $data['start_at'])
                                    ->where('end_at', $data['end_at'])
                                    ->exists();

                        if ($cek){
                            Notification::make()
                                ->title('Error')
                                ->body('Jadwal dengan kombinasi hari & jam ini sudah ada.')
                                ->danger()
                                ->send();
                            
                            $this->halt();

                            throw ValidationException::withMessages([
                                'start_at' => ['Jadwal dengan kombinasi hari & jam ini sudah ada.'],
                            ]);
                        }
                        
                        $data['kode'] = $kombinasi;

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
