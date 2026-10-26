<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\JadwalPeriksa;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PoliController extends Controller
{
    public function dashboard()
    {
        $pasienId = Auth::id();

        // 1. Antrian aktif pasien yang belum selesai diperiksa
        $antrianAktif = DaftarPoli::with(['jadwalPeriksa.dokter.poli'])
            ->where('id_pasien', $pasienId)
            ->where(function ($q) {
                $q->whereNull('status')
                  ->orWhere('status', '!=', 'selesai');
            })
            ->doesntHave('periksa')
            ->latest()
            ->first();

        // 2. Tentukan nomor antrian yang SEDANG DILAYANI pada jadwal pasien
        $sedangDilayani = 0;
        if ($antrianAktif) {
            // Prioritas 1: Antrian yang statusnya 'sedang_diperiksa' (form dokter sedang terbuka)
            $sedangProses = DaftarPoli::where('id_jadwal', $antrianAktif->id_jadwal)
                ->where('status', 'sedang_diperiksa')
                ->first();

            if ($sedangProses) {
                $sedangDilayani = $sedangProses->no_antrian;
            } else {
                // Prioritas 2: Antrian terakhir yang sudah diperiksa / selesai
                $terakhirSelesai = DaftarPoli::where('id_jadwal', $antrianAktif->id_jadwal)
                    ->where(function ($q) {
                        $q->where('status', 'selesai')
                          ->orWhereHas('periksa');
                    })
                    ->orderBy('no_antrian', 'desc')
                    ->first();

                $sedangDilayani = $terakhirSelesai ? $terakhirSelesai->no_antrian : 0;
            }
        }

        // 3. Ambil semua jadwal dokter dan status sedang dilayani untuk tabel bawah
        $jadwals = JadwalPeriksa::with(['dokter.poli'])->get()->map(function ($jadwal) {
            $sedangProses = DaftarPoli::where('id_jadwal', $jadwal->id)
                ->where('status', 'sedang_diperiksa')
                ->first();

            if ($sedangProses) {
                $jadwal->sedang_dilayani = $sedangProses->no_antrian;
            } else {
                $terakhirSelesai = DaftarPoli::where('id_jadwal', $jadwal->id)
                    ->where(function ($q) {
                        $q->where('status', 'selesai')
                          ->orWhereHas('periksa');
                    })
                    ->orderBy('no_antrian', 'desc')
                    ->first();

                $jadwal->sedang_dilayani = $terakhirSelesai ? $terakhirSelesai->no_antrian : 0;
            }

            return $jadwal;
        });

        return view('pasien.dashboard', compact('antrianAktif', 'sedangDilayani', 'jadwals'));
    }

    // Endpoint JSON untuk live update nomor antrian tanpa reload
    public function antrianLive()
    {
        $pasienId = Auth::id();

        $antrianAktif = DaftarPoli::where('id_pasien', $pasienId)
            ->where(function ($q) {
                $q->whereNull('status')
                  ->orWhere('status', '!=', 'selesai');
            })
            ->doesntHave('periksa')
            ->latest()
            ->first();

        $antrianPasienSedangDilayani = 0;
        if ($antrianAktif) {
            $sedangProses = DaftarPoli::where('id_jadwal', $antrianAktif->id_jadwal)
                ->where('status', 'sedang_diperiksa')
                ->first();

            if ($sedangProses) {
                $antrianPasienSedangDilayani = $sedangProses->no_antrian;
            } else {
                $terakhirSelesai = DaftarPoli::where('id_jadwal', $antrianAktif->id_jadwal)
                    ->where(function ($q) {
                        $q->where('status', 'selesai')
                          ->orWhereHas('periksa');
                    })
                    ->orderBy('no_antrian', 'desc')
                    ->first();

                $antrianPasienSedangDilayani = $terakhirSelesai ? $terakhirSelesai->no_antrian : 0;
            }
        }

        // Ambil status sedang dilayani untuk seluruh jadwal di tabel
        $jadwalStatus = JadwalPeriksa::pluck('id')->mapWithKeys(function ($id) {
            $sedangProses = DaftarPoli::where('id_jadwal', $id)
                ->where('status', 'sedang_diperiksa')
                ->first();

            if ($sedangProses) {
                return [$id => $sedangProses->no_antrian];
            }

            $terakhirSelesai = DaftarPoli::where('id_jadwal', $id)
                ->where(function ($q) {
                    $q->where('status', 'selesai')
                      ->orWhereHas('periksa');
                })
                ->orderBy('no_antrian', 'desc')
                ->first();

            return [$id => $terakhirSelesai ? $terakhirSelesai->no_antrian : 0];
        });

        return response()->json([
            'antrian_pasien_sedang_dilayani' => $antrianPasienSedangDilayani,
            'jadwals'                        => $jadwalStatus,
        ]);
    }

    public function get()
    {
        $user = Auth::user();
        $polis = Poli::all();
        $jadwal = JadwalPeriksa::with('dokter', 'dokter.poli')->get();

        return view('pasien.daftar', [
            'user'    => $user,
            'polis'   => $polis,
            'jadwals' => $jadwal,
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'id_poli'   => 'required|exists:poli,id',
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan'   => 'nullable|string',
            'id_pasien' => 'required|exists:users,id',
        ]);

        // KETENTUAN: Pasien tidak dapat mendaftar ke dua poli sekaligus sebelum pemeriksaan selesai
        $antrianAktif = DaftarPoli::where('id_pasien', $request->id_pasien)
            ->where(function ($q) {
                $q->whereNull('status')
                  ->orWhere('status', '!=', 'selesai');
            })
            ->doesntHave('periksa')
            ->exists();

        if ($antrianAktif) {
            return redirect()
                ->back()
                ->with('message', 'Anda masih memiliki antrian yang belum selesai diperiksa! Tidak dapat mendaftar ke poli lain.')
                ->with('type', 'error');
        }

        $jumlahSudahDaftar = DaftarPoli::where('id_jadwal', $request->id_jadwal)->count();

        DaftarPoli::create([
            'id_pasien'  => $request->id_pasien,
            'id_jadwal'  => $request->id_jadwal,
            'keluhan'    => $request->keluhan,
            'no_antrian' => $jumlahSudahDaftar + 1,
            'status'     => 'menunggu',
        ]);

        return redirect('/pasien/dashboard')
            ->with('message', 'Berhasil mendaftar ke poli. Silakan pantau antrian Anda.')
            ->with('type', 'success');
    }
    
public function riwayat()
{
    $riwayats = DaftarPoli::with([
        'jadwalPeriksa.dokter.poli',
        'periksa'
    ])
    ->where('id_pasien', Auth::id())
    ->latest()
    ->get();

    return view('pasien.riwayat.index', compact('riwayats'));
}

public function detailRiwayat($id)
{
    $daftarPoli = DaftarPoli::with([
        'jadwalPeriksa.dokter.poli',
        'periksa.detailPeriksas.obat'
    ])
    ->where('id_pasien', Auth::id())
    ->findOrFail($id);

    if (!$daftarPoli->periksa) {
        return redirect()->route('pasien.riwayat')->with('error', 'Pemeriksaan belum selesai.');
    }

    return view('pasien.riwayat.detail', compact('daftarPoli'));
}
}