<?php

namespace App\Http\Controllers;

use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Notifications\BuktiBayarUploadedNotification;
use App\Notifications\PembayaranLunasNotification;

class PembayaranController extends Controller
{
    // ================= SISI PASIEN =================
    public function indexPasien()
    {
        $pasienId = auth()->user()->id;

        $tagihan = Periksa::with(['daftarPoli.jadwalPeriksa.dokter.poli', 'detailPeriksa.obat'])
            ->whereHas('daftarPoli', function ($q) use ($pasienId) {
                $q->where('id_pasien', $pasienId);
            })
            ->latest('tgl_periksa')
            ->get();

        return view('pasien.pembayaran.index', compact('tagihan'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_pembayaran.required' => 'File bukti pembayaran wajib diupload.',
            'bukti_pembayaran.image'    => 'File harus berupa gambar.',
            'bukti_pembayaran.mimes'    => 'Format gambar harus jpeg, png, atau jpg.',
            'bukti_pembayaran.max'      => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Eager load relasi ke User Dokter
        $periksa = Periksa::with(['daftarPoli.jadwalPeriksa.dokter'])->findOrFail($id);

        if ($request->hasFile('bukti_pembayaran')) {
            if ($periksa->bukti_pembayaran && Storage::disk('public')->exists($periksa->bukti_pembayaran)) {
                Storage::disk('public')->delete($periksa->bukti_pembayaran);
            }

            $path = $request->file('bukti_pembayaran')->store('bukti-bayar', 'public');

            $periksa->update([
                'bukti_pembayaran'  => $path,
                'status_pembayaran' => 'menunggu_konfirmasi',
            ]);

            // Kirim notifikasi ke User Dokter
            $dokter = $periksa->daftarPoli->jadwalPeriksa->dokter ?? null;
            if ($dokter) {
                $dokter->notify(new BuktiBayarUploadedNotification($periksa));
            }
        }

        return back()->with([
            'message' => 'Bukti pembayaran berhasil diupload! Menunggu konfirmasi admin.',
            'type'    => 'success'
        ]);
    }

    // ================= SISI ADMIN =================
    public function indexAdmin()
    {
        $pembayaran = Periksa::with(['daftarPoli.pasien', 'daftarPoli.jadwalPeriksa.dokter.poli'])
            ->latest('tgl_periksa')
            ->get();

        return view('admin.pembayaran.index', compact('pembayaran'));
    }

    public function konfirmasiPembayaran($id)
    {
        // Eager load relasi ke User Pasien
        $periksa = Periksa::with(['daftarPoli.pasien'])->findOrFail($id);

        $periksa->update([
            'status_pembayaran' => 'lunas'
        ]);

        // Kirim notifikasi ke User Pasien
        $pasien = $periksa->daftarPoli->pasien ?? null;
        if ($pasien) {
            $pasien->notify(new PembayaranLunasNotification($periksa));
        }

        return back()->with([
            'message' => 'Pembayaran berhasil dikonfirmasi dan status menjadi lunas.',
            'type'    => 'success'
        ]);
    }
}