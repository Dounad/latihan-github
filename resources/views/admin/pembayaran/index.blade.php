<x-layouts.app title="Verifikasi Pembayaran">
    <div class="py-6 px-4 space-y-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Verifikasi Pembayaran</h2>
            <p class="text-xs text-slate-500 mt-1">Konfirmasi bukti transfer tagihan pasien poli.</p>
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
                            <th class="p-4">Pasien</th>
                            <th class="p-4">Poli</th>
                            <th class="p-4">Total Tagihan</th>
                            <th class="p-4">Bukti Bayar</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pembayaran as $item)
                            <tr class="hover:bg-slate-50/60">
                                <td class="p-4">
                                    <div class="font-bold text-slate-800">{{ $item->daftarPoli->pasien->nama ?? '-' }}</div>
                                    <div class="text-xs text-slate-400">No RM: {{ $item->daftarPoli->pasien->no_rm ?? '-' }}</div>
                                </td>
                                <td class="p-4">{{ $item->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}</td>
                                <td class="p-4 font-bold text-slate-800">Rp {{ number_format($item->biaya_periksa, 0, ',', '.') }}</td>
                                <td class="p-4">
                                    @if($item->bukti_pembayaran)
                                        <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}" target="_blank" class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-md text-xs font-semibold hover:bg-blue-100 border border-blue-200 inline-flex items-center gap-1">
                                            <i class="fas fa-external-link-alt text-[10px]"></i> Cek Bukti
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Belum ada</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($item->status_pembayaran == 'lunas')
                                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Lunas</span>
                                    @elseif($item->status_pembayaran == 'menunggu_konfirmasi')
                                        <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">Menunggu Konfirmasi</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-semibold">Belum Bayar</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    @if($item->status_pembayaran == 'menunggu_konfirmasi')
                                        <form action="{{ route('admin.pembayaran.konfirmasi', $item->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm">
                                                Konfirmasi Lunas
                                            </button>
                                        </form>
                                    @elseif($item->status_pembayaran == 'lunas')
                                        <span class="text-xs text-emerald-600 font-semibold"><i class="fas fa-check-double"></i> Terverifikasi</span>
                                    @else
                                        <span class="text-xs text-slate-400">Menunggu Pasien</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-slate-400 text-sm">Tidak ada data tagihan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>