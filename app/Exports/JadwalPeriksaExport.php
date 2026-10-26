<?php

namespace App\Exports;

use App\Models\JadwalPeriksa;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JadwalPeriksaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return JadwalPeriksa::where('id_dokter', Auth::id())->get();
    }

    public function headings(): array
    {
        return ['ID', 'Hari', 'Jam Mulai', 'Jam Selesai', 'Status Aktif'];
    }

    public function map($jadwal): array
    {
        return [
            $jadwal->id,
            ucfirst($jadwal->hari),
            substr($jadwal->jam_mulai, 0, 5),
            substr($jadwal->jam_selesai, 0, 5),
            $jadwal->is_active ? 'Aktif' : 'Tidak Aktif',
        ];
    }
}