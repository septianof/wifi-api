<?php

namespace App\Filament\Widgets;

use App\Models\Pelanggan;
use Filament\Widgets\Widget;

class NotifikasiJatuhTempo extends Widget
{
    protected static string $view = 'filament.resources.admin-resource.widgets.notifikasi-jatuh-tempo';

    protected int | string | array $columnSpan = 'full';

    public function getPelangganJatuhTempoProperty()
    {
        return Pelanggan::where('status_bayar', false)
            ->whereDate('tanggal_jatuh_tempo', '<=', now()->startOfDay())
            ->get();
    }
}
