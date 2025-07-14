<?php

namespace App\Filament\Resources\PelangganResource\Pages;

use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PelangganResource;

class CreatePelanggan extends CreateRecord
{
    protected static string $resource = PelangganResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $today = Carbon::now();

        $jatuhTempo = $today->copy()->day(25);
        if ($today->day > 25) {
            $jatuhTempo->addMonth();
        }

        $data['tanggal_jatuh_tempo'] = $jatuhTempo;
        $data['status_bayar'] = false;

        return $data;
    }
}
