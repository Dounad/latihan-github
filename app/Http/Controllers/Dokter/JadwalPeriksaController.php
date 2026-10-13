<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalPeriksaController extends Controller
{
    public function index()
    {
        $jadwals = JadwalPeriksa::where('id_dokter', Auth::id())->get();
        return view('dokter.jadwal-periksa.index', compact('jadwals'));
    }

    public function create()
    {
        return view('dokter.jadwal-periksa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari'        => 'required|string|max:50',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        JadwalPeriksa::create([
            'id_dokter'   => Auth::id(),
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil ditambahkan')
            ->with('type', 'success');
    }

    public function edit(JadwalPeriksa $jadwal_periksa)
    {
        if ($jadwal_periksa->id_dokter !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan');
        }

        return view('dokter.jadwal-periksa.edit', compact('jadwal_periksa'));
    }

    public function update(Request $request, JadwalPeriksa $jadwal_periksa)
    {
        if ($jadwal_periksa->id_dokter !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan');
        }

        $request->validate([
            'hari'        => 'required|string|max:50',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $jadwal_periksa->update([
            'hari'        => $request->hari,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil diperbarui')
            ->with('type', 'success');
    }

    public function destroy(JadwalPeriksa $jadwal_periksa)
    {
        if ($jadwal_periksa->id_dokter !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan');
        }

        $jadwal_periksa->delete();

        return redirect()->route('jadwal-periksa.index')
            ->with('message', 'Jadwal periksa berhasil dihapus')
            ->with('type', 'success');
    }
}