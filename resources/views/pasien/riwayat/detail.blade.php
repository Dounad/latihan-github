<x-layouts.app title="Detail Pemeriksaan Pasien">
    <div class="py-6 px-4 max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('pasien.riwayat') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1">
                &larr; Kembali ke Riwayat
            </a>
            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-xs font-bold">
                Pemeriksaan Selesai
            </span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
            {{-- Info Header --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pb-6 border-b border-slate-100 text-sm">
                <div>
                    <span class="text-xs text-slate-400 block">Poli & Dokter</span>
                    <h3 class="font-bold text-slate-800 text-lg">{{ $daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</h3>
                    <p class="text-slate-600 text-xs">{{ $daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }}</p>
                </div>
                <div class="md:text-right">
                    <span class="text-xs text-slate-400 block">Waktu Pemeriksaan</span>
                    <p class="font-semibold text-slate-700">
                        {{ \Carbon\Carbon::parse($daftarPoli->periksa->tgl_periksa)->translatedFormat('l, d F Y - H:i') }}
                    </p>
                    <span class="text-xs text-slate-400">No. Antrian: <b class="text-slate-700">{{ $daftarPoli->no_antrian }}</b></span>
                </div>
            </div>

            {{-- Catatan / Diagnosa --}}
            <div>
                <h4 class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-2">Catatan / Diagnosa Dokter</h4>
                <div class="p-4 bg-slate-50 rounded-xl text-slate-700 text-sm leading-relaxed border border-slate-100">
                    {{ $daftarPoli->periksa->catatan ?: 'Tidak ada catatan dokter.' }}
                </div>
            </div>

            {{-- Daftar Obat & Total Biaya --}}
            <div>
                <h4 class="text-xs uppercase font-bold text-slate-400 tracking-wider mb-2">Resep Obat & Rincian Biaya</h4>
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-700 text-xs font-semibold uppercase">
                            <tr>
                                <th class="p-3">Nama Layanan / Obat</th>
                                <th class="p-3">Kemasan</th>
                                <th class="p-3 text-right">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            <tr>
                                <td class="p-3 font-medium text-slate-800">Biaya Jasa Dokter</td>
                                <td class="p-3 text-slate-400">-</td>
                                <td class="p-3 text-right font-medium text-slate-700">Rp 150.000</td>
                            </tr>
                            @forelse($daftarPoli->periksa->detailPeriksas as $detail)
                                <tr>
                                    <td class="p-3 font-medium text-slate-800">{{ $detail->obat->nama_obat ?? '-' }}</td>
                                    <td class="p-3 text-slate-500">{{ $detail->obat->kemasan ?? '-' }}</td>
                                    <td class="p-3 text-right text-slate-700">Rp {{ number_format($detail->obat->harga ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-3 text-center text-slate-400 italic">Tidak ada resep obat yang diberikan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-slate-50 font-bold border-t border-slate-200 text-sm">
                            <tr>
                                <td colspan="2" class="p-3 text-slate-800">Total Biaya Pemeriksaan</td>
                                <td class="p-3 text-right text-blue-600">
                                    Rp {{ number_format($daftarPoli->periksa->biaya_periksa, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>