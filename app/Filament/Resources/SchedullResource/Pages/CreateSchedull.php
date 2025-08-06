<?php

namespace App\Filament\Resources\SchedullResource\Pages;

use Filament\Actions;
use App\Models\Schedull;
use Illuminate\Support\Carbon;
use function Laravel\Prompts\form;
use Filament\Notifications\Notification;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SchedullResource;

class CreateSchedull extends CreateRecord
{
    protected static string $resource = SchedullResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $hari = \App\Models\Day::find($data['day_id'])->nama_hari;
        $startAt =  Carbon::parse($data['start_at'])->format('H:i');
        $endAt =  Carbon::parse($data['end_at'])->format('H:i');

        $kombinasi = $hari. '/' .$startAt. '/' .$endAt;

        $cek = Schedull::where('day_id', $data['day_id'])
                    ->where('start_at', $startAt)
                    ->where('end_at', $endAt)
                    ->exists();

        if ($cek){
            Notification::make()
                ->title('Error')
                ->body('Jadwal sudah ada')
                ->danger()
                ->send();
            
            $this->halt();
        }

        $data['kode'] = $kombinasi;

        return $data;
    }
}
