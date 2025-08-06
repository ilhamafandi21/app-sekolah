<?php

namespace App\Filament\Resources\DayResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Schedull;
use Filament\Forms\Form;
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('kode'),  
                Forms\Components\TimePicker::make('start_at'),
                Forms\Components\TimePicker::make('end_at'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('kode'),
                Tables\Columns\TextColumn::make('start_at'),
                Tables\Columns\TextColumn::make('end_at'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (Array $data){
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
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
