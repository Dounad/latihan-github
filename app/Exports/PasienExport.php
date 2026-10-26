<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PasienExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::where('role', 'pasien')->get();
    }

    public function headings(): array
    {
        return ['ID', 'Nama Pasien', 'No. RM', 'No. KTP', 'No. HP', 'Alamat'];
    }

    public function map($pasien): array
    {
        return [
            $pasien->id,$pasien->nama ?? $pasien->name,$pasien->no_rm ?? '-',
            $pasien->no_ktp ?? '-',
            $pasien->no_hp ?? '-',
            $pasien->alamat ?? '-',
        ];
    }
}