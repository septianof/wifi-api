<x-filament::widget>
    <x-filament::card>
        @if ($this->pelangganJatuhTempo->count())
            <div class="text-red-700 font-semibold mb-2">
                ⚠️ {{ $this->pelangganJatuhTempo->count() }} pelanggan belum bayar dan sudah jatuh tempo:
            </div>
            <ul class="list-disc ml-5 text-sm text-red-600">
                @foreach ($this->pelangganJatuhTempo as $pelanggan)
                    <li>
                        {{ $pelanggan->nama }} – 
                        {{ \Carbon\Carbon::parse($pelanggan->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-green-600 font-semibold">
                ✅ Tidak ada pelanggan yang jatuh tempo hari ini.
            </div>
        @endif
    </x-filament::card>
</x-filament::widget>