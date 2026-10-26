<header class="bg-base-100 border-b border-base-300 h-16 flex items-center px-6 gap-4 sticky top-0 z-30 shadow-sm">

    {{-- Mobile Hamburger --}}
    <button onclick="toggleSidebar()" class="btn btn-square btn-ghost lg:hidden">
        <i data-lucide="menu" class="w-5 h-5"></i>
    </button>

    {{-- Breadcrumb --}}
    <div class="flex-1">
        <div class="flex items-center gap-2 text-sm">
            <span class="text-base-content/50">Poliklinik</span>
            <i data-lucide="chevron-right" class="w-4 h-4 text-base-content/30"></i>
            <span class="font-semibold text-base-content">
                {{ $title ?? 'Dashboard' }}
            </span>
        </div>
    </div>

    {{-- Fullscreen --}}
    <button onclick="toggleFullscreen()" class="btn btn-square btn-ghost">
        <i id="fsIcon" class="fas fa-expand w-5 h-5"></i>
    </button>

    {{-- ================= DROPDOWN NOTIFIKASI ================= --}}
    @php
        $unreadCount = auth()->user()->unreadNotifications->count();
        $notifications = auth()->user()->notifications()->take(5)->get();
    @endphp

    <div class="dropdown dropdown-end relative">
        <label tabindex="0" class="btn btn-square btn-ghost relative">
            <i class="fas fa-bell text-lg"></i>
            @if($unreadCount > 0)
                <span class="absolute top-1.5 right-1.5 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-rose-600 text-[10px] text-white font-bold items-center justify-center">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                </span>
            @endif
        </label>

        <div tabindex="0" class="dropdown-content menu p-3 shadow-lg bg-white rounded-2xl w-80 border border-slate-100 z-50 mt-2">
            <div class="flex items-center justify-between pb-2 mb-2 border-b border-slate-100">
                <span class="font-bold text-sm text-slate-800">Notifikasi</span>
                @if($unreadCount > 0)
                    <form action="{{ route('notifications.readAll') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-blue-600 hover:underline">Tandai semua dibaca</button>
                    </form>
                @endif
            </div>

            <div class="space-y-1.5 max-h-64 overflow-y-auto">
                @forelse($notifications as $notif)
                    <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left p-2.5 rounded-xl transition text-xs {{ $notif->read_at ? 'bg-white hover:bg-slate-50 text-slate-500' : 'bg-blue-50/70 hover:bg-blue-100/70 text-slate-800 font-semibold' }}">
                            <div class="text-xs font-bold text-slate-800">{{ $notif->data['title'] ?? 'Notifikasi' }}</div>
                            <div class="text-[11px] text-slate-600 mt-0.5 line-clamp-2">{{ $notif->data['message'] ?? '' }}</div>
                            <div class="text-[9px] text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</div>
                        </button>
                    </form>
                @empty
                    <div class="text-center py-4 text-xs text-slate-400">Tidak ada notifikasi</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- User Profile Link (Nama & Avatar bisa diklik) --}}
    <a href="{{ route('profile.edit') }}" 
       class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-base-200/70 transition-all duration-200 group">
        
        <div class="text-right hidden sm:block">
            <div class="text-sm font-semibold leading-tight group-hover:text-primary transition-colors">
                {{ auth()->user()->nama ?? (auth()->user()->name ?? 'Pengguna') }}
            </div>
            <div class="text-xs text-base-content/50 leading-tight capitalize">
                {{ auth()->user()->role ?? 'Admin Sistem' }}
            </div>
        </div>

        <div class="avatar">
            <div class="w-10 h-10 rounded-full bg-primary text-primary-content flex items-center justify-center font-semibold text-sm shadow-sm group-hover:scale-105 group-hover:ring-2 group-hover:ring-primary/40 transition-all">
                <span>
                    {{ strtoupper(substr(auth()->user()->nama ?? (auth()->user()->name ?? 'U'), 0, 1)) }}
                </span>
            </div>
        </div>
    </a>

</header>

<script>
    function toggleFullscreen() {
        const icon = document.getElementById('fsIcon');

        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
            icon.classList.remove('fa-expand');
            icon.classList.add('fa-compress');
        } else {
            document.exitFullscreen();
            icon.classList.remove('fa-compress');
            icon.classList.add('fa-expand');
        }
    }
</script>