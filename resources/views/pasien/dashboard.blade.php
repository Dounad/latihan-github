<x-layouts.app title="Dashboard Pasien">
    <div class="py-6 px-4 space-y-6">

        {{-- ================= BANNER ANTRIAN AKTIF ANDA ================= --}}
        @if($antrianAktif)
            <div class="w-full bg-[#4361ee] text-white rounded-2xl p-6 shadow-md flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <span class="text-xs uppercase tracking-wider font-semibold text-blue-200">ANTRIAN AKTIF ANDA</span>
                    <div class="mt-2">
                        <span class="text-[11px] text-blue-200 block">Poliklinik</span>
                        <h2 class="text-2xl font-bold leading-tight">
                            {{ $antrianAktif->jadwalPeriksa->dokter->poli->nama_poli ?? 'Poli Umum' }}
                        </h2>
                    </div>

                    <div class="mt-2">
                        <span class="text-[11px] text-blue-200 block">Dokter</span>
                        <p class="text-base font-semibold">
                            {{ $antrianAktif->jadwalPeriksa->dokter->nama ?? '-' }}
                        </p>
                    </div>

                    <div class="mt-2">
                        <span class="text-[11px] text-blue-200 block">Jadwal Periksa</span>
                        <p class="text-xs text-blue-100 font-medium">
                            {{ $antrianAktif->jadwalPeriksa->hari }} ({{ substr($antrianAktif->jadwalPeriksa->jam_mulai, 0, 5) }} - {{ substr($antrianAktif->jadwalPeriksa->jam_selesai, 0, 5) }})
                        </p>
                    </div>
                </div>

                {{-- Kotak Nomor Antrian & Sedang Dilayani --}}
                <div class="flex items-center gap-4">
                    {{-- Kotak Nomor Pasien --}}
                    <div class="bg-blue-600/60 border border-blue-400/40 rounded-xl px-6 py-4 text-center min-w-[120px]">
                        <span class="text-[10px] uppercase font-bold text-blue-200 tracking-wider block">Nomor Anda</span>
                        <span class="text-4xl font-extrabold text-white mt-1 block">
                            {{ $antrianAktif->no_antrian }}
                        </span>
                    </div>

                    {{-- Kotak Sedang Dilayani (Live Update) --}}
                    <div class="bg-white rounded-xl px-6 py-4 text-center min-w-[130px] shadow-sm">
                        <span class="text-[10px] uppercase font-bold text-slate-700 tracking-wider block">SEDANG DILAYANI</span>
                        <span id="liveNomorSedangDilayani" class="text-4xl font-extrabold text-[#4361ee] mt-1 block">
                            {{ $sedangDilayani > 0 ? $sedangDilayani : '0' }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-[9px] text-slate-400 font-semibold mt-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live Update
                        </span>
                    </div>
                </div>
            </div>
        @else
            {{-- Jika tidak memiliki antrian aktif (Tampilkan Pesan Kosong / Banner Info) --}}
            <div class="w-full bg-slate-50 border border-dashed border-slate-300 rounded-2xl p-6 text-center text-slate-500">
                <i class="fas fa-calendar-check text-3xl text-slate-400 mb-2"></i>
                <h4 class="font-bold text-slate-700">Tidak Ada Antrian Aktif</h4>
                <p class="text-xs text-slate-400 mt-1">Anda belum terdaftar pada antrian dokter mana pun hari ini.</p>
                <div class="mt-4">
                    <a href="{{ url('/pasien/daftar') }}" class="btn btn-primary btn-sm rounded-xl">
                        <i class="fas fa-plus mr-1"></i> Daftar Periksa Poli
                    </a>
                </div>
            </div>
        @endif

        {{-- ================= TABEL DAFTAR JADWAL POLIKLINIK & DOKTER ================= --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Daftar Jadwal Poliklinik & Dokter</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Informasi jadwal praktik dan antrian yang sedang dilayani saat ini</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 text-xs uppercase font-semibold">
                        <tr>
                            <th class="p-4 w-12 text-center">No</th>
                            <th class="p-4">Poli</th>
                            <th class="p-4">Dokter</th>
                            <th class="p-4">Hari</th>
                            <th class="p-4">Jam Periksa</th>
                            <th class="p-4 text-center">Sedang Dilayani</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($jadwals as $index => $jadwal)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="p-4 text-center font-medium">{{ $index + 1 }}</td>
                                <td class="p-4 font-semibold text-slate-800">
                                    {{ $jadwal->dokter->poli->nama_poli ?? '-' }}
                                </td>
                                <td class="p-4">{{ $jadwal->dokter->nama ?? '-' }}</td>
                                <td class="p-4 capitalize">{{ $jadwal->hari }}</td>
                                <td class="p-4">
                                    {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                </td>
                                <td class="p-4 text-center">
                                    <span id="jadwal-dilayani-{{ $jadwal->id }}" class="px-3 py-1 bg-blue-50 text-blue-700 font-bold rounded-lg text-xs inline-block min-w-[36px]">
                                        {{ $jadwal->sedang_dilayani > 0 ? $jadwal->sedang_dilayani : '-' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-slate-400">Tidak ada jadwal dokter yang tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Load build script Vite yang memuat Echo & Reverb --}}
    @vite(['resources/js/app.js'])

    {{-- Script Listener Laravel Echo --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof window.Echo !== 'undefined') {
                window.Echo.channel('antrian-channel')
                    .listen('.antrian.updated', (e) => {
                        // 1. Update kolom Sedang Dilayani pada tabel jadwal
                        const cellElem = document.getElementById(`jadwal-dilayani-${e.idJadwal}`);
                        if (cellElem) {
                            cellElem.textContent = e.noAntrian > 0 ? e.noAntrian : '-';
                        }

                        // 2. Update Kotak SEDANG DILAYANI pada banner jika jadwal sesuai dengan antrian aktif pasien
                        @if($antrianAktif)
                            if ({{ $antrianAktif->id_jadwal }} == e.idJadwal) {
                                const bannerSedangDilayani = document.getElementById('liveNomorSedangDilayani');
                                if (bannerSedangDilayani) {
                                    bannerSedangDilayani.textContent = e.noAntrian > 0 ? e.noAntrian : '0';
                                }
                            }
                        @endif
                    });
            } else {
                console.warn('Laravel Echo belum terdefinisi. Pastikan npm run dev sedang berjalan.');
            }
        });
    </script>
</x-layouts.app>