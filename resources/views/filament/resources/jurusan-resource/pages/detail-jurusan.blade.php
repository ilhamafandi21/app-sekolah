<div class="bg-gray-900 text-gray-200 text-xs border border-gray-800 rounded-md p-2 shadow-sm w-full min-w-[100px] max-w-xs text-[10px] leading-tight">
    <h2 class="text-white font-semibold mb-2">🧾 Detail Jurusan</h2>

    <div class="grid grid-cols-2 gap-x-2 gap-y-1 leading-tight">
        <div class="text-gray-400">Kode</div>
        <div class="text-white">{{ $record->kode }}</div>

        <div class="text-gray-400">Tingkat</div>
        <div class="text-white">{{ $record->tingkat?->nama_tingkat ?? '-' }}</div>

        <div class="text-gray-400">Jurusan</div>
        <div class="text-white">{{ $record->nama_jurusan }}</div>

        <div class="text-gray-400">Keterangan</div>
        <div class="text-white">{{ $record->keterangan ?: '-' }}</div>
    </div>

    <div class="text-[11px] text-gray-500 border-t border-gray-800 pt-2 mt-2 leading-tight">
        <div>Dibuat: {{ $record->created_at?->format('d M Y H:i') }}</div>
        <div>Diubah: {{ $record->updated_at?->format('d M Y H:i') }}</div>
    </div>
</div>
