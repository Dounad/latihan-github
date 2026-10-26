<x-layouts.app title="Profil Pengguna">

    <div class="max-w-3xl mx-auto py-8 px-4 space-y-6">

        {{-- Header Halaman + Tombol Kembali --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Profil Saya</h2>
                <p class="text-xs text-slate-500 mt-1">Informasi detail akun dan pengaturan kata sandi.</p>
            </div>

            @php
                $userRole = strtolower(auth()->user()->role ?? '');
                $backUrl = match($userRole) {
                    'admin' => route('admin.dashboard'),
                    'dokter' => route('dokter.dashboard'),
                    'pasien' => route('pasien.dashboard'),
                    default => url()->previous(),
                };
            @endphp

            <a href="{{ $backUrl }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl border border-slate-200 transition">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>

        {{-- Alert Notifikasi Sukses --}}
        @if(session('message'))
            <div class="p-4 rounded-xl flex items-center gap-3 border shadow-sm {{ session('type') == 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200' }}">
                <i class="fas fa-circle-check text-emerald-600 text-lg"></i>
                <span class="font-medium text-sm">{{ session('message') }}</span>
            </div>
        @endif

        {{-- 1. Kartu Informasi Akun --}}
        <div class="card bg-white shadow-sm border border-slate-200/80 rounded-2xl p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fas fa-user-circle text-blue-600 text-base"></i> Informasi Akun
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Nama Lengkap</label>
                    <input type="text" value="{{ $user->nama ?? ($user->name ?? '-') }}" readonly
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 font-medium cursor-not-allowed focus:outline-none" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Peran / Role</label>
                    <div class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold capitalize text-blue-600">
                        {{ $user->role ?? 'Pengguna' }}
                    </div>
                </div>

                @if(!empty($user->no_rm))
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Nomor Rekam Medis (No RM)</label>
                    <input type="text" value="{{ $user->no_rm }}" readonly
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 cursor-not-allowed focus:outline-none" />
                </div>
                @endif

                @if(!empty($user->no_hp))
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Nomor HP / Telepon</label>
                    <input type="text" value="{{ $user->no_hp }}" readonly
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 cursor-not-allowed focus:outline-none" />
                </div>
                @endif

                @if(!empty($user->alamat))
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Alamat</label>
                    <textarea readonly rows="2"
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-700 cursor-not-allowed focus:outline-none">{{ $user->alamat }}</textarea>
                </div>
                @endif
            </div>
        </div>

        {{-- 2. Kartu Form Ganti Password --}}
        <div class="card bg-white shadow-sm border border-slate-200/80 rounded-2xl p-6">
            <h3 class="text-base font-bold text-slate-800 mb-4 pb-3 border-b border-slate-100 flex items-center gap-2">
                <i class="fas fa-lock text-blue-600 text-sm"></i> Ganti Kata Sandi
            </h3>

            <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Password Lama --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        Password Lama <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="current_password" required
                               placeholder="Masukkan kata sandi lama Anda"
                               class="w-full px-4 py-2.5 bg-white border @error('current_password') border-rose-500 @else border-slate-300 @enderror rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10" />
                        <button type="button" onclick="togglePassword('current_password')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye text-sm"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Password Baru --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Password Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password" required
                                   placeholder="Minimal 8 karakter"
                                   class="w-full px-4 py-2.5 bg-white border @error('password') border-rose-500 @else border-slate-300 @enderror rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10" />
                            <button type="button" onclick="togglePassword('password')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password Baru --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Ulangi Password Baru <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" required
                                   placeholder="Ketik ulang password baru"
                                   class="w-full px-4 py-2.5 bg-white border @error('password_confirmation') border-rose-500 @else border-slate-300 @enderror rounded-lg text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10" />
                            <button type="button" onclick="togglePassword('password_confirmation')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="text-rose-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-3 flex justify-end">
                    <button type="submit" 
                            class="bg-primary hover:bg-primary/90 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-layouts.app>

<script>
    function togglePassword(inputId) {
        const input = document.querySelector('input[name="' + inputId + '"]');
        const icon = input.nextElementSibling.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>