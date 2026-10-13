<x-layouts.app title="Edit Jadwal Periksa">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Edit Jadwal Periksa
        </h2>

        <a href="{{ route('jadwal-periksa.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition">
            <i class="fas fa-arrow-left text-xs"></i>
            Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="card bg-base-100 shadow-md rounded-2 border max-w-2xl">
        <div class="card-body p-6">
            <form action="{{ route('jadwal-periksa.update', $jadwal_periksa->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Hari --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Hari</span>
                    </label>
                    <select name="hari" required class="select select-bordered w-full rounded-xl @error('hari') select-error @enderror">
                        <option value="" disabled>-- Pilih Hari --</option>
                        @php
                            $daftarHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                        @endphp
                        @foreach($daftarHari as $h)
                            <option value="{{ $h }}" {{ old('hari', $jadwal_periksa->hari) == $h ? 'selected' : '' }}>
                                {{ $h }}
                            </option>
                        @endforeach
                    </select>
                    @error('hari')
                        <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Jam Mulai & Jam Selesai --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-700">Jam Mulai</span>
                        </label>
                        <input type="time" name="jam_mulai" value="{{ old('jam_mulai', date('H:i', strtotime($jadwal_periksa->jam_mulai))) }}" required
                               class="input input-bordered w-full rounded-xl @error('jam_mulai') input-error @enderror" />
                        @error('jam_mulai')
                            <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-700">Jam Selesai</span>
                        </label>
                        <input type="time" name="jam_selesai" value="{{ old('jam_selesai', date('H:i', strtotime($jadwal_periksa->jam_selesai))) }}" required
                               class="input input-bordered w-full rounded-xl @error('jam_selesai') input-error @enderror" />
                        @error('jam_selesai')
                            <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Status --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Status</span>
                    </label>
                    <select name="status" class="select select-bordered w-full rounded-xl">
                        <option value="aktif" {{ old('status', strtolower($jadwal_periksa->status ?? 'aktif')) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak aktif" {{ old('status', strtolower($jadwal_periksa->status ?? '')) == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                {{-- Tombol Aksi --}}
                <div class="pt-4 flex items-center gap-3">
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-white text-sm font-semibold rounded-xl transition">
                        <i class="fas fa-check text-xs"></i>
                        Perbarui
                    </button>
                    <a href="{{ route('jadwal-periksa.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>