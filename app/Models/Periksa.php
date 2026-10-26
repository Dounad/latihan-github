<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periksa extends Model
{
    // Arahkan ke nama tabel 'periksa' yang benar di database
    protected $table = 'periksa';

    protected $fillable = [
        'id_daftar_poli',
        'tgl_periksa',
        'catatan',
        'biaya_periksa',
        'bukti_pembayaran',
        'status_pembayaran',
    ];

    public function daftarPoli()
    {
        return $this->belongsTo(DaftarPoli::class, 'id_daftar_poli');
    }

    public function detailPeriksa()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_periksa');
    }
    public function detailPeriksas()
    {
        return $this->hasMany(DetailPeriksa::class, 'id_periksa');
    }
}