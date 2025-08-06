<?php

namespace App\Filament\Resources\SchedullResource\Pages;

use Filament\Actions;
use App\Models\Schedull;
use Illuminate\Support\Carbon;
use function Laravel\Prompts\form;
use Filament\Notifications\Notification;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\SchedullResource;
use Illuminate\Validation\ValidationException;

class CreateSchedull extends CreateRecord
{
    protected static string $resource = SchedullResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $hari = \App\Models\Day::find($data['day_id'])->nama_hari;
        $startAt =  Carbon::parse($data['start_at'])->format('H:i');
        $endAt =  Carbon::parse($data['end_at'])->format('H:i');

        $kombinasi = $hari. '/' .$startAt. '/' .$endAt;

        $cek = Schedull::where('kode', $kombinasi)
                    ->where('day_id', $data['day_id'])
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
    }
}
