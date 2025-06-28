<ul class="space-y-2">
    @foreach ($getRecord()->documents as $dokumen)
        @php
            $url = \Illuminate\Support\Facades\Storage::url($dokumen->image);
        @endphp
        <li>
            <a href="{{ $url }}" target="_blank" class="text-primary-600 underline hover:text-primary-800" rel="noopener noreferrer">
                {{ $dokumen->name ?? 'Lihat File' }}
            </a>
        </li>
    @endforeach
</ul>
