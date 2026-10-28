<x-layouts.app title="Riwayat Pembayaran">
    <div class="py-6 px-4 space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Tagihan & Pembayaran</h2>
            <p class="text-xs text-slate-500 mt-1">Daftar tagihan periksa dan unggah bukti transfer pembayaran.</p>
        </div>

        @if(session('message'))
            <div class="p-4 rounded-xl flex items-center gap-3 border shadow-sm bg-emerald-50 text-emerald-800 border-emerald-200">
                <i class="fas fa-circle-check text-emerald-600"></i>
                <span class="font-medium text-sm">{{ session('message') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold border-b">
                        <tr>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Poli / Dokter</th>
                            <th class="p-4">Total Biaya</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Bukti</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($tagihan as $item)
                            <tr class="hover:bg-slate-50/60">
                                <td class="p-4">{{ \Carbon\Carbon::parse($item->tgl_periksa)->format('d M Y') }}</td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-800">{{ $item->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</div>
                                    <div class="text-xs text-slate-400">{{ $item->daftarPoli->jadwalPeriksa->dokter->nama ?? '-' }}</div>
                                </td>
                                <td class="p-4 font-bold text-slate-800">
                                    Rp {{ number_format($item->biaya_periksa, 0, ',', '.') }}
                                </td>
                                <td class="p-4">
                                    @if($item->status_pembayaran == 'lunas')
                                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Lunas</span>
                                    @elseif($item->status_pembayaran == 'menunggu_konfirmasi')
                                        <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">Menunggu Verifikasi</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-semibold">Belum Bayar</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($item->bukti_pembayaran)
                                        <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}" target="_blank" class="text-blue-600 hover:underline text-xs flex items-center gap-1">
                                            <i class="fas fa-image"></i> Lihat Foto
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">Belum diunggah</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('pasien.pembayaran.show', $item->id) }}" 
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary hover:bg-primary/90 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                                        <i class="fas fa-file-invoice-dollar text-xs"></i>
                                        Detail Tagihan
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-slate-400 text-sm">Belum ada riwayat tagihan periksa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>