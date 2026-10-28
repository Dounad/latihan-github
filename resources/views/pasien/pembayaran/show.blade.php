<x-layouts.app title="Detail Tagihan & Pembayaran">

    {{-- Notifikasi Flash Message --}}
    @if(session('message'))
        <div class="mb-4 p-4 rounded-xl {{ session('type') == 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
            {{ session('message') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('pasien.pembayaran.index') }}" class="text-sm text-slate-500 hover:text-primary inline-flex items-center gap-1 mb-2">
                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Tagihan & Pembayaran
            </a>
            <h2 class="text-2xl font-bold text-slate-800">Rincian Pembayaran</h2>
        </div>

        <div>
            @if($pembayaran->status_pembayaran === 'lunas')
                <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                    Lunas
                </span>
            @elseif($pembayaran->status_pembayaran === 'menunggu_konfirmasi')
                <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                    Menunggu Konfirmasi
                </span>
            @else
                <span class="px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                    Belum Bayar
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Kiri: Rincian Biaya & Resep Obat (2 Kolom) --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 border-b pb-3">Informasi Pemeriksaan</h3>
            
            <div class="grid grid-cols-2 gap-4 text-sm mb-6 text-slate-600">
                <div>
                    <span class="block text-xs text-slate-400">Tanggal Pemeriksaan</span>
                    <span class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($pembayaran->tgl_periksa)->format('d F Y') }}</span>
                </div>
                <div>
                    <span class="block text-xs text-slate-400">Poli & Dokter Pemeriksa</span>
                    <span class="font-medium text-slate-800">
                        {{ $pembayaran->daftarPoli->jadwalPeriksa->dokter->poli->nama_poli ?? 'Poli' }} - 
                        {{ $pembayaran->daftarPoli->jadwalPeriksa->dokter->nama ?? $pembayaran->daftarPoli->jadwalPeriksa->dokter->name }}
                    </span>
                </div>
                <div class="col-span-2">
                    <span class="block text-xs text-slate-400">Keluhan Pasien</span>
                    <span class="text-slate-700">{{ $pembayaran->daftarPoli->keluhan ?? '-' }}</span>
                </div>
                <div class="col-span-2">
                    <span class="block text-xs text-slate-400">Catatan / Diagnosa Dokter</span>
                    <span class="text-slate-700">{{ $pembayaran->catatan ?? '-' }}</span>
                </div>
            </div>

            <h3 class="text-base font-bold text-slate-800 mb-3 border-b pb-3">Rincian Tagihan</h3>
            <table class="w-full text-sm mb-6">
                <thead>
                    <tr class="text-slate-400 text-xs uppercase border-b text-left">
                        <th class="pb-2">Keterangan</th>
                        <th class="pb-2 text-right">Biaya</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr>
                        <td class="py-3">Jasa Pemeriksaan Dokter & Klinik</td>
                        <td class="py-3 text-right">Rp {{ number_format($biayaJasaDokter, 0, ',', '.') }}</td>
                    </tr>
                    @forelse($pembayaran->detailPeriksa as $detail)
                    <tr>
                        <td class="py-3">
                            <span class="font-medium text-slate-800">{{ $detail->obat->nama_obat ?? 'Obat' }}</span>
                            <span class="text-xs text-slate-400 block">{{ $detail->obat->kemasan ?? '-' }}</span>
                        </td>
                        <td class="py-3 text-right">Rp {{ number_format($detail->obat->harga ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="py-3 text-slate-400 italic">Tidak ada peresepan obat</td>
                        <td class="py-3 text-right">Rp 0</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-slate-200 font-bold text-slate-900 text-base">
                        <td class="pt-4">Total Biaya</td>
                        <td class="pt-4 text-right text-primary">Rp {{ number_format($pembayaran->biaya_periksa, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Kolom Kanan: Rekening Transfer & Form Upload Bukti (1 Kolom) --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-800 mb-3 border-b pb-3">Instruksi Pembayaran</h3>
                <div class="bg-slate-50 p-4 rounded-xl mb-4 text-xs text-slate-600">
                    <p class="font-semibold text-slate-800 mb-1">Transfer Bank:</p>
                    <p class="text-sm font-mono font-bold text-slate-800">BCA: 123-456-7890</p>
                    <p>A.N. Poliklinik Sehat</p>
                </div>

                @if($pembayaran->bukti_pembayaran)
                    <div class="mb-4">
                        <span class="block text-xs text-slate-400 mb-2">Bukti yang telah diunggah:</span>
                        <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" target="_blank" class="block border rounded-xl overflow-hidden hover:opacity-90">
                            <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" class="w-full h-40 object-cover" alt="Bukti Transfer">
                        </a>
                    </div>
                @endif
            </div>

            @if($pembayaran->status_pembayaran !== 'lunas')
                <form action="{{ route('pasien.pembayaran.upload', $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Upload Bukti Transfer (JPG/PNG)</label>
                        <input type="file" name="bukti_pembayaran" required class="file-input file-input-bordered file-input-sm w-full rounded-xl" />
                        @error('bukti_pembayaran')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-primary hover:bg-primary/90 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                        <i class="fas fa-upload mr-1"></i> {{ $pembayaran->bukti_pembayaran ? 'Upload Ulang Bukti' : 'Kirim Bukti Pembayaran' }}
                    </button>
                </form>
            @else
                <div class="p-3 bg-emerald-50 text-emerald-700 text-center rounded-xl text-xs font-semibold">
                    <i class="fas fa-check-circle mr-1"></i> Pembayaran telah diverifikasi
                </div>
            @endif
        </div>

    </div>

</x-layouts.app>