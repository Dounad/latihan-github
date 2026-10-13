<x-layouts.app title="Edit Dokter">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Edit Dokter
        </h2>

        <a href="{{ route('dokter.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition">
            <i class="fas fa-arrow-left text-xs"></i>
            Kembali
        </a>
    </div>

    {{-- Form Card --}}
    <div class="card bg-base-100 shadow-md rounded-2 border max-w-3xl">
        <div class="card-body p-6">
            <form action="{{ route('dokter.update', $dokter->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Nama Dokter --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Nama Lengkap</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama', $dokter->nama) }}" required
                           class="input input-bordered w-full rounded-xl @error('nama') input-error @enderror" />
                    @error('nama')
                        <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Poli --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Poli</span>
                    </label>
                    <select name="id_poli" required class="select select-bordered w-full rounded-xl @error('id_poli') select-error @enderror">
                        <option value="" disabled>-- Pilih Poli --</option>
                        @foreach ($polis as $poli)
                            <option value="{{ $poli->id }}" {{ old('id_poli', $dokter->id_poli) == $poli->id ? 'selected' : '' }}>
                                {{ $poli->nama_poli }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_poli')
                        <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- No KTP --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-700">No. KTP</span>
                        </label>
                        <input type="text" name="no_ktp" value="{{ old('no_ktp', $dokter->no_ktp) }}" required maxlength="16"
                               class="input input-bordered w-full rounded-xl @error('no_ktp') input-error @enderror" />
                        @error('no_ktp')
                            <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- No HP --}}
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-700">No. HP</span>
                        </label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $dokter->no_hp) }}" required maxlength="15"
                               class="input input-bordered w-full rounded-xl @error('no_hp') input-error @enderror" />
                        @error('no_hp')
                            <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Email & Password --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-700">Email</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email', $dokter->email) }}" required
                               class="input input-bordered w-full rounded-xl @error('email') input-error @enderror" />
                        @error('email')
                            <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold text-slate-700">Password Baru (Opsional)</span>
                        </label>
                        <input type="password" name="password" minlength="6"
                               placeholder="Kosongkan jika tidak diubah"
                               class="input input-bordered w-full rounded-xl @error('password') input-error @enderror" />
                        @error('password')
                            <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Alamat --}}
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold text-slate-700">Alamat</span>
                    </label>
                    <textarea name="alamat" required rows="3"
                              class="textarea textarea-bordered w-full rounded-xl @error('alamat') textarea-error @enderror">{{ old('alamat', $dokter->alamat) }}</textarea>
                    @error('alamat')
                        <span class="text-rose-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="pt-4 flex items-center gap-3">
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-white text-sm font-semibold rounded-xl transition">
                        <i class="fas fa-check text-xs"></i>
                        Perbarui
                    </button>
                    <a href="{{ route('dokter.index') }}" 
                       class="inline-flex items-center px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-semibold rounded-xl transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>