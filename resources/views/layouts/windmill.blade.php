<!doctype html>
<html lang="id" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') - SimDes</title>
  <!-- Tailwind via CDN for quick integration; replace with compiled CSS for production -->
  <script src="https://cdn.tailwindcss.com"></script>
  @livewireStyles
  <!-- Alpine is provided via Livewire bundle; avoid loading Alpine CDN to prevent multiple instances -->
  <style>
    /* small helper to keep content area full height */
    html,body,#app{height:100%;}
    /* hide elements with x-cloak until Alpine initializes */
    [x-cloak] { display: none !important; }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">
  <div id="app" class="flex h-screen">
    <!-- Mobile overlay sidebar -->
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 md:hidden">
      <div class="absolute inset-0 bg-black opacity-50" @click="sidebarOpen = false"></div>
      <aside class="absolute left-0 top-0 bottom-0 w-64 bg-white border-r p-4 overflow-auto">
        <div class="flex items-center justify-between mb-4">
  <script>
    // Livewire hook debugging: logs processed messages and errors
    if (window.Livewire) {
      try {
        window.Livewire.hook('message.processed', (message, component) => {
          console.log('Livewire: message processed for component', component?.name ?? component);
        });

        window.Livewire.hook('message.failed', (message, component) => {
          console.warn('Livewire: message failed', message, component);
        });
      } catch (e) {
        console.log('Livewire hook error', e);
      }
    }
  </script>
          <div class="font-bold text-lg">SimDes</div>
          <button @click="sidebarOpen = false" class="p-2">&times;</button>
        </div>
        <nav>
          @if(session()->has('kepala_keluarga_id'))
            <a href="{{ route('kepala.dashboard') }}" class="block py-2 px-2 rounded hover:bg-gray-100">Dashboardsss</a>
            <a href="{{ route('kepala.anggota.index') }}" class="block py-2 px-2 rounded hover:bg-gray-100">Anggota Keluarga</a>
            <a href="{{ route('kepala.anggota.create') }}" class="block py-2 px-2 rounded hover:bg-gray-100">Tambah Anggota Keluarga</a>
            <a href="{{ route('kepala.kk.print') }}" class="block py-2 px-2 rounded hover:bg-gray-100">Cetak Kartu Keluarga</a>
            <a href="{{ route('kepala.feedback.create') }}" class="block py-2 px-2 rounded hover:bg-gray-100">Kritik dan Saran</a>
          @else
            <a href="{{ route('home') }}" class="block py-2 px-2 rounded hover:bg-gray-100">Home</a>
            <a href="{{ route('admin.kepala.index') }}" class="block py-2 px-2 rounded hover:bg-gray-100">Kepala Keluarga</a>
            <a href="{{ route('admin.penduduk.index') }}" class="block py-2 px-2 rounded hover:bg-gray-100">Penduduk</a>
            <a href="{{ route('admin.kepala.create') }}" class="block py-2 px-2 rounded hover:bg-gray-100">Tambah Kepala</a>
          @endif
        </nav>
      </aside>
    </div>

    <!-- Desktop sidebar (collapsible) -->
    <aside :class="sidebarCollapsed ? 'w-20' : 'w-64'" class="bg-white hidden md:block border-r transition-width duration-150">
      <div class="p-4 flex items-center">
        <div class="font-bold text-lg" x-show="!sidebarCollapsed" x-cloak>SimDes</div>
        <div class="ml-auto">
          <button class="p-1 rounded hover:bg-gray-100" @click="sidebarCollapsed = !sidebarCollapsed" title="Toggle sidebar">
            <span x-text="sidebarCollapsed ? '›' : '‹'"></span>
          </button>
        </div>
      </div>
      <nav class="p-4">
        @if(session()->has('kepala_keluarga_id'))
          {{-- Sidebar khusus untuk kepala keluarga --}}
          <a href="{{ route('kepala.dashboard') }}" class="flex items-center py-2 px-2 rounded hover:bg-gray-100"><span class="flex-1" x-show="!sidebarCollapsed" x-cloak>Dashboard</span></a>
          <a href="{{ route('kepala.anggota.index') }}" class="flex items-center py-2 px-2 rounded hover:bg-gray-100"><span class="flex-1" x-show="!sidebarCollapsed" x-cloak>Anggota Keluarga</span></a>
          <a href="{{ route('kepala.anggota.create') }}" class="flex items-center py-2 px-2 rounded hover:bg-gray-100"><span class="flex-1" x-show="!sidebarCollapsed" x-cloak>Tambah Anggota Keluarga</span></a>
          <a href="{{ route('kepala.kk.print') }}" class="flex items-center py-2 px-2 rounded hover:bg-gray-100"><span class="flex-1" x-show="!sidebarCollapsed" x-cloak>Cetak Kartu Keluarga</span></a>
          <a href="{{ route('kepala.feedback.create') }}" class="flex items-center py-2 px-2 rounded hover:bg-gray-100"><span class="flex-1" x-show="!sidebarCollapsed" x-cloak>Kritik dan Saran</span></a>
        @else
          {{-- Default sidebar (admin / public) --}}
          <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2 px-2 rounded hover:bg-gray-100"><span class="flex-1" x-show="!sidebarCollapsed" x-cloak>Home</span></a>
          <a href="{{ route('admin.kepala.index') }}" class="flex items-center py-2 px-2 rounded hover:bg-gray-100"><span class="flex-1" x-show="!sidebarCollapsed" x-cloak>Kepala Keluarga</span></a>
          <a href="{{ route('admin.penduduk.index') }}" class="flex items-center py-2 px-2 rounded hover:bg-gray-100"><span class="flex-1" x-show="!sidebarCollapsed" x-cloak>Penduduk</span></a>
          <a href="{{ route('admin.kepala.create') }}" class="flex items-center py-2 px-2 rounded hover:bg-gray-100"><span class="flex-1" x-show="!sidebarCollapsed" x-cloak>Tambah Kepala</span></a>
            <a href="{{ route('admin.feedback.index') }}" class="flex items-center py-2 px-2 rounded hover:bg-gray-100"><span class="flex-1" x-show="!sidebarCollapsed" x-cloak>Feedback</span></a>
        @endif
      </nav>
    </aside>

    <div class="flex-1 flex flex-col">
      <!-- Topbar -->
      <header class="bg-white border-b p-4 flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <button class="md:hidden p-2" @click="sidebarOpen = !sidebarOpen">☰</button>
          <div class="flex items-center space-x-3">
            <img src="/logo.png" alt="logo" class="h-8 w-8 object-contain" onerror="this.style.display='none'" />
            <h1 class="text-lg font-semibold">@yield('title', 'Dashboard')</h1>
          </div>
        </div>

        <div class="flex items-center space-x-4">
          {{-- Notifikasi: tampilkan unread untuk Admin (Auth) atau Kepala (session) --}}
          @php
            $unread = 0;
            $notifs = collect();
            $routeName = null;
            if (\Illuminate\Support\Facades\Auth::check()) {
                $user = \Illuminate\Support\Facades\Auth::user();
                $unread = $user->unreadNotifications()->count();
                $notifs = $user->unreadNotifications()->take(10)->get();
                $routeName = 'admin.notifications.go';
            } elseif (session()->has('kepala_keluarga_id')) {
                $kepala = \App\Models\KepalaKeluarga::find(session('kepala_keluarga_id'));
                if ($kepala) {
                    $unread = $kepala->unreadNotifications()->count();
                    $notifs = $kepala->unreadNotifications()->take(10)->get();
                }
                $routeName = 'kepala.notifications.go';
            }
          @endphp

          <div x-data="{notifOpen:false}" class="relative">
            <button @click="notifOpen = !notifOpen" title="Notifikasi" class="p-2 rounded hover:bg-gray-100 relative">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
              @if($unread > 0)
                <span class="absolute -top-1 -right-1 bg-red-600 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">{{ $unread }}</span>
              @endif
            </button>

            <div x-show="notifOpen" x-cloak @click.away="notifOpen = false" class="origin-top-right absolute right-0 mt-2 w-96 bg-white border rounded shadow-lg z-50">
              <div class="p-3 border-b flex items-center justify-between">
                <strong>Notifikasi</strong>
                <a href="#" @click.prevent="notifOpen = false" class="text-xs text-gray-500">Tutup</a>
              </div>
              <div class="max-h-72 overflow-auto">
                @if($notifs->isEmpty())
                  <div class="p-3 text-sm text-gray-600">Tidak ada notifikasi baru.</div>
                @else
                  @foreach($notifs as $n)
                    @php $data = (array) $n->data; @endphp
                    <a href="{{ $routeName ? route($routeName, $n->id) : '#' }}" class="block px-3 py-2 hover:bg-gray-50 border-b">
                      <div class="flex items-start">
                        <div class="flex-1">
                          <div class="text-sm font-medium text-gray-800">{{ $data['title'] ?? 'Notifikasi' }}</div>
                          <div class="text-xs text-gray-600">{{ $data['body'] ?? '' }}</div>
                        </div>
                        <div class="ml-2 text-xs text-gray-400">{{ $n->created_at->diffForHumans() }}</div>
                      </div>
                    </a>
                  @endforeach
                @endif
              </div>
              <div class="p-2 text-center border-t">
                @if(\Illuminate\Support\Facades\Auth::check())
                  <a href="{{ route('admin.notifications.index') }}" class="text-xs text-indigo-600">Lihat semua</a>
                @elseif(session()->has('kepala_keluarga_id'))
                  <a href="{{ route('kepala.notifications.index') }}" class="text-xs text-indigo-600">Lihat semua</a>
                @else
                  <a href="{{ route('home') }}" class="text-xs text-indigo-600">Lihat semua</a>
                @endif
              </div>
            </div>
          </div>

            {{-- Kepala logout or login link --}}
            @if(session()->has('kepala_keluarga_id'))
            <form action="{{ route('kepala.logout') }}" method="POST">@csrf<button class="px-3 py-1 bg-white border rounded">Keluar</button></form>
            @else
            {{-- <a href="{{ route('kepala.login') }}" class="px-3 py-1 bg-white border rounded">Login Kepala</a> --}}
            @endif

          {{-- Admin auth (uses Laravel auth) --}}
          @guest
            {{-- <a href="/login" class="px-3 py-1 bg-indigo-600 text-white rounded">Admin Masuk</a> --}}
          @else
            <form action="{{ route('logout') }}" method="POST" class="inline">@csrf<button class="px-3 py-1 bg-white border rounded">Logout</button></form>
          @endguest
        </div>
      </header>

      <!-- Content -->
      <main class="flex-1 overflow-auto p-6">
        @yield('content')
      </main>
    </div>
  </div>

  @stack('scripts')
  @livewireScripts
  <script>
    // Debug helper: show Livewire presence and log wire:click interactions
    try {
      console.log('Livewire present?', !!window.Livewire);
      if (window.Livewire) {
        try {
          Livewire.hook('message.sent', (message, component) => {
            console.log('Livewire: message.sent', message, component?.name ?? component);
          });
          Livewire.hook('message.processed', (message, component) => {
            console.log('Livewire: message.processed', component?.name ?? component);
          });
        } catch (e) {
          console.debug('Livewire hook error', e);
        }
      }

      document.addEventListener('click', function (e) {
        var el = e.target.closest('[wire\\:click], [wire\\:click\\.prevent], [wire\\:model]');
        if (el) {
          console.log('Clicked wire element:', el.tagName, el.outerHTML.slice(0,200));
        }
      }, true);
    } catch (err) {
      console.log('Livewire debug script error', err);
    }
  </script>
    
</body>
</html>
