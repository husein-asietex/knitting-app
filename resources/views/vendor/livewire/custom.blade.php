@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-4"
        style="border-top:1px solid var(--border)">
        <p class="text-xs" style="color:var(--slate)">
            Menampilkan <span class="font-semibold"
                style="color:var(--ink)">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
            dari <span class="font-semibold" style="color:var(--ink)">{{ $paginator->total() }}</span> data
        </p>
        <div class="flex items-center gap-1">
            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <span class="btn btn-ghost !py-1.5 !px-2.5 opacity-40 cursor-not-allowed">
                    <livewire:elements.icons.chevron-left class="w-4 h-4" />
                </span>
            @else
                <button wire:click="previousPage" class="btn btn-ghost !py-1.5 !px-2.5">
                    <livewire:elements.icons.chevron-left class="w-4 h-4" />
                </button>
            @endif

            {{-- Page numbers --}}
            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if ($page === $paginator->currentPage())
                    <span class="btn btn-primary !py-1.5 !px-3 text-xs">{{ $page }}</span>
                @elseif (abs($page - $paginator->currentPage()) <= 2)
                    <button wire:click="gotoPage({{ $page }})"
                        class="btn btn-ghost !py-1.5 !px-3 text-xs">{{ $page }}</button>
                @elseif ($page === 1 || $page === $paginator->lastPage())
                    <button wire:click="gotoPage({{ $page }})"
                        class="btn btn-ghost !py-1.5 !px-3 text-xs">{{ $page }}</button>
                @elseif (abs($page - $paginator->currentPage()) === 3)
                    <span class="px-1 text-xs" style="color:var(--slate)">…</span>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" class="btn btn-ghost !py-1.5 !px-2.5">
                    <livewire:elements.icons.chevron-right class="w-4 h-4" />
                </button>
            @else
                <span class="btn btn-ghost !py-1.5 !px-2.5 opacity-40 cursor-not-allowed">
                    <livewire:elements.icons.chevron-right class="w-4 h-4" />
                </span>
            @endif
        </div>
        <div class="flex items-center gap-2 text-xs" style="color:var(--slate)">
            <span>Tampilkan</span>
            <select wire:model.live="perPage" class="input !py-1 !px-2 !text-xs !w-auto">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
            <span>/ halaman</span>
        </div>
    </div>
@else
    <div class="px-5 py-3" style="border-top:1px solid var(--border)">
        <p class="text-xs" style="color:var(--slate)">
            Total <span class="font-semibold" style="color:var(--ink)">{{ $paginator->total() }}</span> data
        </p>
    </div>
@endif
