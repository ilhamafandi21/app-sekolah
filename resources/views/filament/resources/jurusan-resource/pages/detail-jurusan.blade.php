<div class="rounded-xl bg-gradient-to-br from-gray-800 via-gray-900 to-black p-6 shadow-xl border border-gray-700 text-gray-200 space-y-4">
    <h2 class="text-lg font-semibold tracking-wide text-white">
        🧾 Informasi Jurusan
    </h2>

    <ul class="space-y-2 text-sm">
        <li>
            <span class="block text-gray-400">Kode</span>
            <span class="text-base font-medium text-white">{{ $record->kode }}</span>
        </li>
        <li>
            <span class="block text-gray-400">Tingkat</span>
            <span class="text-base font-medium text-white">{{ $record->tingkat?->nama_tingkat ?? '-' }}</span>
        </li>
        <li>
            <span class="block text-gray-400">Jurusan</span>
            <span class="text-base font-medium text-white">{{ $record->nama_jurusan }}</span>
        </li>
    </ul>

    <div class="border-t border-gray-700 pt-4 text-xs text-gray-500">
        Dibuat: {{ $record->created_at?->format('d M Y H:i') }} <br>
        Diubah: {{ $record->updated_at?->format('d M Y H:i') }}
    </div>
</div>
