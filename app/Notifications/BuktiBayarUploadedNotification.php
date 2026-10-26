<?php

namespace App\Notifications;

use App\Models\Periksa;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BuktiBayarUploadedNotification extends Notification
{
    use Queueable;

    public $periksa;

    public function __construct(Periksa $periksa)
    {
        $this->periksa = $periksa;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $namaPasien = $this->periksa->daftarPoli->pasien->nama 
            ?? ($this->periksa->daftarPoli->pasien->name ?? 'Pasien');

        return [
            'title'      => 'Bukti Pembayaran Baru',
            'message'    => "Pasien {$namaPasien} telah mengunggah bukti pembayaran.",
            'id_periksa' => $this->periksa->id,
            'url'        => route('dokter.dashboard'),
        ];
    }
}