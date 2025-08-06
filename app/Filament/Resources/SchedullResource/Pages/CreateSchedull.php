<?php

namespace App\Filament\Resources\SchedullResource\Pages;

use Filament\Actions;
use Illuminate\Support\Carbon;
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

        dd($kombinasi);


        return $data;
    }
}
