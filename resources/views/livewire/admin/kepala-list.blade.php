<div class="max-w-6xl mx-auto px-6 py-8">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">Data Kepala Keluarga</h1>
    <div class="flex items-center space-x-2">
      <input
        id="kepala-search"
        type="text"
        wire:model.debounce.300ms="search"
        wire:input.debounce.300ms="$set('search', $event.target.value)"
        placeholder="Cari nama atau NIK..."
        autocomplete="off"
        class="border rounded px-3 py-2 {{ (isset($kepalas) && $kepalas->total() === 0 && strlen(trim($search)) > 0) ? 'border-red-400' : '' }}"
      />
      <div wire:loading wire:target="search" class="text-sm text-gray-500">Mencari...</div>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($kepalas as $k)
      <div wire:key="kepala-{{ $k->id }}" class="bg-white rounded shadow p-6">
        <div class="flex items-start justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-800">{{ $k->nama }}</h3>
            <p class="text-sm text-gray-500">NIK: {{ $k->nik }}</p>
            <p class="text-sm text-gray-500 mt-1">Anggota: <span class="font-medium text-gray-700">{{ $k->anggota_count }}</span></p>
          </div>
          <div class="text-right">
            <button wire:click="toggleOpen({{ $k->id }})" class="inline-flex items-center px-3 py-2 bg-white border border-gray-200 text-gray-700 rounded-md shadow-sm hover:bg-gray-50 text-sm">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
              <span>{{ ($openId ?? null) === $k->id ? 'Hide' : 'View more' }}</span>
            </button>
          </div>
        </div>

  <div class="mt-4 anggota-list" style="display: {{ ($openId ?? null) === $k->id ? 'block' : 'none' }};">
          @if(isset($loadedAnggota[$k->id]) && count($loadedAnggota[$k->id]) > 0)
            <ul class="text-sm text-gray-700 space-y-2">
              @foreach($loadedAnggota[$k->id] as $a)
                <li>{{ $a['nama'] }} — {{ $a['status_dalam_keluarga'] ?? '-' }}</li>
              @endforeach
            </ul>
          @else
            <div class="text-sm text-gray-500">Tidak ada anggota.</div>
          @endif
        </div>
      </div>
    @empty
      <div class="col-span-full text-center text-gray-500 py-8">Tidak ada hasil yang cocok untuk "<span class="font-medium">{{ $search }}</span>"</div>
    @endforelse
  </div>

  <div class="mt-6">{{ $kepalas->links('livewire.pagination-tailwind') }}</div>
  
  <script>
  // Robust initialization: attach input listener after DOM ready and after Livewire processes the component.
  (function(){
    function init(){
      try{
        const input = document.getElementById('kepala-search');
        if(!input) return;
        // avoid attaching multiple times
        if(input.__kepala_debug_attached) return;
        input.__kepala_debug_attached = true;

        input.addEventListener('input', function(){
          console.debug('[KepalaList] input event, value=', input.value);
        });

        // find livewire root and component id
        const root = input.closest('[wire\:id]');
        if(root){
          console.debug('[KepalaList] found Livewire root', root.getAttribute('wire:id'));
          try{
            if(window.Livewire){
              const comp = Livewire.find(root.getAttribute('wire:id'));
              console.debug('[KepalaList] Livewire.find ->', !!comp);
            }
          }catch(e){ console.debug('[KepalaList] Livewire.find error', e); }
        }

        console.debug('[KepalaList] Livewire present?', !!window.Livewire);
      }catch(e){ console.debug('[KepalaList] debug init error', e); }
    }

    if(document.readyState === 'loading'){
      document.addEventListener('DOMContentLoaded', init);
    } else {
      init();
    }

    if(window.Livewire){
      try{ Livewire.hook('message.processed', init); } catch(e){}
    }

    // Fallback: if native wire:model isn't triggering XHRs for some reason,
    // attach a Livewire-aware fallback that sets the component's `search` property
    // directly via the Livewire JS API. This is conservative: it only activates
    // when a Livewire component root is found and Livewire is available.
    function attachFallback(){
      try{
        const input = document.getElementById('kepala-search');
        if(!input) return;
        // find the Livewire root id for this input. Try closest first, then scan all Livewire roots
        let root = input.closest('[wire\:id]');
        let comp = null;
        if(window.Livewire && root){
          const lwId = root.getAttribute('wire:id');
          comp = Livewire.find(lwId);
        }

        // fallback: if no root found via closest, search all Livewire roots and pick a component
        if(!comp && window.Livewire){
          const roots = document.querySelectorAll('[wire\:id]');
          for(const r of roots){
            try{
              const c = Livewire.find(r.getAttribute('wire:id'));
              if(c && (c?.name === 'admin.kepala-list' || (r.contains(input)))){ comp = c; root = r; break; }
            }catch(e){ /* ignore */ }
          }
        }

        if(!comp) {
          // nothing we can attach to
          console.debug('[KepalaList] attachFallback: no Livewire component instance found');
          return;
        }

        // Avoid double-attaching
        if(input.__kepala_livewire_fallback) return;
        input.__kepala_livewire_fallback = true;

        function debounce(fn, wait){let t;return function(...args){clearTimeout(t);t=setTimeout(()=>fn.apply(this,args),wait);};}
        const send = debounce(function(val){
          try{
            // set the input value (in case something else mutated it) and dispatch a native input event
            try{ input.value = val; input.dispatchEvent(new Event('input', { bubbles: true })); console.debug('[KepalaList] fallback dispatched native input ->', val); }catch(e){ console.debug('[KepalaList] dispatch input error', e); }
            // also call Livewire component set as a fallback
            try{ comp.set('search', val); console.debug('[KepalaList] fallback set search ->', val); }catch(e){console.debug('[KepalaList] fallback set error', e);}          
          }catch(e){ console.debug('[KepalaList] fallback send error', e); }
        }, 300);

        input.addEventListener('input', function(e){ send(e.target.value); });
        console.debug('[KepalaList] Livewire fallback attached to', lwId);
      }catch(e){ console.debug('[KepalaList] attachFallback error', e); }
    }

    // Try to attach fallback now and whenever Livewire processes a message
    try{ attachFallback(); if(window.Livewire) Livewire.hook('message.processed', attachFallback); }catch(e){}
  })();
  </script>

  {{-- Fallback removed: native Livewire bindings (wire:model) used for realtime search. --}}

</div>
