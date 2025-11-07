@if ($paginator->hasPages())
  <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700 leading-5">
          Menampilkan
          <span class="font-medium">{{ $paginator->firstItem() }}</span>
          hingga
          <span class="font-medium">{{ $paginator->lastItem() }}</span>
          dari
          <span class="font-medium">{{ $paginator->total() }}</span>
          hasil
        </p>
      </div>

      <div>
        <span class="relative z-0 inline-flex shadow-sm rounded-md">
          {{-- Previous Page Link --}}
          @if ($paginator->onFirstPage())
            <span aria-disabled="true" aria-label="Previous">
              <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-l-md">&lt;</span>
            </span>
          @else
            <button wire:click.prevent="gotoPage({{ $paginator->currentPage() - 1 }})" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-l-md" aria-label="Previous">&lt;</button>
          @endif

          {{-- Pagination Elements --}}
          @foreach (range(1, $paginator->lastPage()) as $page)
            @if ($page == $paginator->currentPage())
              <span aria-current="page" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-indigo-600 border border-gray-300">{{ $page }}</span>
            @else
              <button wire:click.prevent="gotoPage({{ $page }})" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">{{ $page }}</button>
            @endif
          @endforeach

          {{-- Next Page Link --}}
          @if ($paginator->hasMorePages())
            <button wire:click.prevent="gotoPage({{ $paginator->currentPage() + 1 }})" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-r-md" aria-label="Next">&gt;</button>
          @else
            <span aria-disabled="true" aria-label="Next">
              <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md">&gt;</span>
            </span>
          @endif
        </span>
      </div>
    </div>
  </nav>
@endif
