<?php

namespace App\Filament\Resources\RombelsSubjectsSchedullsTeacherResource\Pages;

use App\Filament\Resources\RombelsSubjectsSchedullsTeacherResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRombelsSubjectsSchedullsTeacher extends CreateRecord
{
    protected static string $resource = RombelsSubjectsSchedullsTeacherResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $kode =  $data['rombel_id']. $data['subject_id']. $data['rombels_subjects_id']. $data['schedull_id'];

        $cekduplikat = \App\Models\RombelsSubjectsSchedullsTeacher::where('rombel_id', $data['rombel_id'])
                                    ->where('subject_id', $data['subject_id'])
                                    ->where('rombels_subjects_id', $data['rombels_subjects_id'])
                                    ->where('schedull_id', $data['schedull_id'])
                                    ->exists();

        if($cekduplikat){
            Notification::make()
                ->title('Error')
                ->body('Data sudah ada')
                ->danger()
                ->send();

                $this->halt();
        }
        $data['kode'] = $kode;
        return $data;
    }
}
