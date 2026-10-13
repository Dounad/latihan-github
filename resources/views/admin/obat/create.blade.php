<x-layouts.app title="Tambah Obat">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Tambah Obat
        </h2>

        <a href="{{ route('obat.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition">
            <i class="fas fa-arrow-left text-xs"></i>
            Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="card bg-base-100 shadow-md rounded-2xl border max-w-2xl">
        <div class="card-body p-6">
            <form action="{{ route('obat.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Nama Obat --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Nama Obat</span>
                    </label>
                    <input type="text" name="nama_obat" value="{{ old('nama_obat') }}" required
                           placeholder="Contoh: Paracetamol 500mg"
                           class="input input-bordered w-full rounded-xl @error('nama_obat') input-error @enderror" />
                    @error('nama_obat')
                        <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Kemasan --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Kemasan</span>
                    </label>
                    <input type="text" name="kemasan" value="{{ old('kemasan') }}" required
                           placeholder="Contoh: Strip / Botol / Tablet"
                           class="input input-bordered w-full rounded-xl @error('kemasan') input-error @enderror" />
                    @error('kemasan')
                        <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Harga --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Harga (Rp)</span>
                    </label>
                    <input type="number" name="harga" value="{{ old('harga') }}" required min="0"
                           placeholder="Contoh: 15000"
                           class="input input-bordered w-full rounded-xl @error('harga') input-error @enderror" />
                    @error('harga')
                        <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="pt-4 flex items-center gap-3">
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-white text-sm font-semibold rounded-xl transition">
                        <i class="fas fa-save text-xs"></i>
                        Simpan
                    </button>
                    <a href="{{ route('obat.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>