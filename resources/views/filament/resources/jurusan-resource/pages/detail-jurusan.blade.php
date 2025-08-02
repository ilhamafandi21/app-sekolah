
    <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
        <li><strong>Kode:</strong> {{ $record->kode }}</li>
        <li><strong>Tingkat:</strong> {{ $record->tingkat?->nama_tingkat ?? '-' }}</li>
        <li><strong>Jurusan:</strong> {{ $record->nama_jurusan }}</li>
    </ul>
