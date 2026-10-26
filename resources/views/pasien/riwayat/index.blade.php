<x-layouts.app title="Riwayat Pendaftaran Poli">
    <div class="py-6 px-4 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Riwayat Pendaftaran Poli</h2>
                <p class="text-xs text-slate-400 mt-1">Daftar riwayat pemeriksaan yang pernah Anda daftarkan</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold">
                        <tr>
                            <th class="p-4 w-12 text-center">No</th>
                            <th class="p-4">Poli</th>
                            <th class="p-4">Dokter</th>
                            <th class="p-4">Jadwal Periksa</th>
                            <th class="p-4 text-center">No. Antrian</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($riwayats as $index => $riwayat)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="p-4 text-center font-medium">{{ $index + 1 }}</td>
                                <td class="p-4 font-semibold text-slate-800">
                                    {{ $riwayat->jadwalPeriksa->dokter->poli->nama_poli ?? '-' }}
                                </td>
                                <td class="p-4">{{ $riwayat->jadwalPeriksa->dokter->nama ?? '-' }}</td>
                                <td class="p-4">
                                    <span class="capitalize">{{ $riwayat->jadwalPeriksa->hari ?? '-' }}</span>
                                    <span class="text-xs text-slate-400 block">
                                        {{ substr($riwayat->jadwalPeriksa->jam_mulai ?? '', 0, 5) }} - {{ substr($riwayat->jadwalPeriksa->jam_selesai ?? '', 0, 5) }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-3 py-1 bg-blue-50 text-blue-700 font-bold rounded-lg text-xs">
                                        {{ $riwayat->no_antrian }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    @if($riwayat->status === 'selesai' || $riwayat->periksa)
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-xs font-semibold">
                                            Selesai Diperiksa
                                        </span>
                                    @elseif($riwayat->status === 'sedang_diperiksa')
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full text-xs font-semibold">
                                            Sedang Diperiksa
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-semibold">
                                            Menunggu Antrian
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-center">
                                    @if($riwayat->status === 'selesai' || $riwayat->periksa)
                                        <a href="{{ route('pasien.riwayat.detail', $riwayat->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#4361ee] hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition">
                                            Detail
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-slate-400">Belum ada riwayat pendaftaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>