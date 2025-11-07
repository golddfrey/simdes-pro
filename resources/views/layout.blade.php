<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>SimDes</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-gray-50 text-gray-800">
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <a href="{{ route('home') }}" class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-indigo-600 text-white rounded flex items-center justify-center font-bold">SD</div>
        <div>
          <h1 class="text-lg font-semibold">SimDes</h1>
        </div>
      </a>

      <nav class="hidden md:flex items-center space-x-6 text-sm">
        @if(session()->has('kepala_keluarga_id'))
          {{-- Kepala keluarga is logged in: show only kepala logout and hide admin login link --}}
          <form action="{{ route('kepala.logout') }}" method="POST" style="display:inline">@csrf
            <button class="px-4 py-2 bg-white text-indigo-600 border border-indigo-600 rounded">Keluar</button>
          </form>
        @else
          {{-- No kepala logged in: show admin login/logout and kepala login link --}}
          @guest
            <!-- Use a plain URL here to avoid RouteNotFoundException when a named 'login' route doesn't exist -->
            <a href="/admin/login" class="px-4 py-2 bg-indigo-600 text-white rounded">Admin Masuk</a>
          @else
            <form action="{{ route('logout') }}" method="POST" style="display:inline">@csrf<button class="px-4 py-2 bg-white text-indigo-600 border border-indigo-600 rounded">Logout</button></form>
          @endguest

          <a href="{{ route('kepala.login') }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded">Login Kepala</a>
        @endif
      </nav>
    </div>
  </header>

  <main class="py-8">
    @yield('content')
  </main>
</body>
</html>
