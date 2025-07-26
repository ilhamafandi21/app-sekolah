<?php

namespace App\Filament\Resources\RombelResource\Pages;

use App\Filament\Resources\RombelResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRombel extends CreateRecord
{
    protected static string $resource = RombelResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data);
        $semestertahun = \App\Models\Semester::with('tahun_ajaran')->find($data['semester_id']);
        $tahun = $semestertahun?->tahun_ajaran?->thn_ajaran;
        $tingkat = $data['tingkat_id'];
        $jurusan = \App\Models\Jurusan::find($data['jurusan_id'])?->kode ?? 'UNV';
        $divisi = $data['divisi'];
        $data['name'] = "{$tahun}/{$tingkat}/{$jurusan}/{$divisi}";
        // Cek duplikat

        if (\App\Models\Rombel::where('name', $data['name'])
            ->where('semester_id', $data['semester_id'])
            ->exists()) {
            // Notifikasi error & hentikan proses
            \Filament\Notifications\Notification::make()
                ->title('Duplikat Rombel')
                ->body("Nama rombel sudah ada: {$data['name']}")
                ->danger()
                ->send();

            // throw ValidationException supaya tidak lanjut create
            throw \Illuminate\Validation\ValidationException::withMessages([
                'name' => 'Nama rombel sudah ada. Silakan cek data!',
            ]);
        }

        return $data;
    }
}
