<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>SimDes</title>
  <script src="https://cdn.tailwindcss.com"></script>
  @livewireStyles
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
  @livewireScripts
  <script>
    // Lightweight helper to emit events to Livewire even if the global
    // Livewire object isn't ready or is exposed under a different name.
    (function(){
      window._lwEmitQueue = window._lwEmitQueue || [];
      window.LivewireEmit = function(eventName){
        var args = Array.prototype.slice.call(arguments, 1);
        // prefer window.Livewire.emit, then window.livewire.emit
        if (window.Livewire && typeof window.Livewire.emit === 'function') {
          return window.Livewire.emit.apply(window.Livewire, [eventName].concat(args));
        }
        if (window.livewire && typeof window.livewire.emit === 'function') {
          return window.livewire.emit.apply(window.livewire, [eventName].concat(args));
        }
        // queue until Livewire loads
        window._lwEmitQueue.push({ eventName: eventName, args: args });
      };

  // try flushing the queue every 200ms for a short period
      var attempts = 0;
      var flushInterval = setInterval(function(){
        attempts++;
        var lw = window.Livewire || window.livewire;
        if (lw && typeof lw.emit === 'function') {
          while(window._lwEmitQueue.length){
            var it = window._lwEmitQueue.shift();
            try { lw.emit.apply(lw, [it.eventName].concat(it.args)); } catch(e){ /* swallow */ }
          }
          clearInterval(flushInterval);
          return;
        }
        if (attempts > 25) { // ~5 seconds
          clearInterval(flushInterval);
        }
      }, 200);
      // Event delegation for buttons with data-livewire-action
      document.addEventListener('click', function(e){
        var btn = e.target.closest && e.target.closest('[data-livewire-action]');
        if (!btn) return;
        var action = btn.getAttribute('data-livewire-action');
        var id = btn.getAttribute('data-livewire-id');
        var lw = window.Livewire || window.livewire;
        if (lw && typeof lw.emit === 'function') {
          try { lw.emit(action, id); } catch(err) { console.error(err); }
          return;
        }
        // fallback to queued emitter
        if (typeof window.LivewireEmit === 'function') {
          window.LivewireEmit(action, id);
        } else {
          // last resort: push to queue
          window._lwEmitQueue = window._lwEmitQueue || [];
          window._lwEmitQueue.push({ eventName: action, args: [id] });
        }
      });
    })();
  </script>
</body>
</html>
