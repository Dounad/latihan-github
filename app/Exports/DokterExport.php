<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DokterExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::where('role', 'dokter')->with('poli')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Dokter',
            'Email',
            'No. KTP',
            'No. HP',
            'Poli',
            'Alamat',
        ];
    }

    public function map($dokter): array
    {
        return [
            $dokter->id,
            $dokter->nama,
            $dokter->email,
            $dokter->no_ktp ?? '-',
            $dokter->no_hp ?? '-',
            $dokter->poli->nama_poli ?? '-',
            $dokter->alamat ?? '-',
        ];
    }
}