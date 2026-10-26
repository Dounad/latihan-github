<?php

namespace App\Exports;

use App\Models\Periksa;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RiwayatPasienExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $dokterId = Auth::id();

        return Periksa::with([
            'daftarPoli.pasien',
            'detailPeriksas.obat'
        ])
        ->whereHas('daftarPoli.jadwalPeriksa', function ($query) use ($dokterId) {
            $query->where('id_dokter', $dokterId);
        })
        ->latest('tgl_periksa')
        ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Periksa',
            'No. RM',
            'Nama Pasien',
            'Catatan / Diagnosa',
            'Obat Diberikan',
            'Biaya Periksa (Rp)',
        ];
    }

    public function map($periksa): array
    {
        static $no = 1;
        $pasien = $periksa->daftarPoli->pasien ?? null;

        // Menggabungkan nama-nama obat jika ada relasi detailPeriksa
        $obatList = '-';
        if ($periksa->detailPeriksas && $periksa->detailPeriksas->isNotEmpty()) {
            $obatList = $periksa->detailPeriksas
                ->map(fn($d) => $d->obat->nama_obat ?? null)
                ->filter()
                ->implode(', ');
        }

        return [
            $no++,
            \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d/m/Y H:i'),
            $pasien->no_rm ?? '-',
            $pasien->nama ?? $pasien->name ?? '-',
            $periksa->catatan ?: '-',
            $obatList ?: '-',
            $periksa->biaya_periksa,
        ];
    }
}