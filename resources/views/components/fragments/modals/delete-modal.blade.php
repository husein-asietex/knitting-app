@props([
    'show' => 'showDeleteModal',
    'close' => 'closeModal',
    'action' => 'delete',
    'title' => 'Hapus data ini?',
    'message' => 'Data yang sudah dihapus tidak dapat dikembalikan.',
    'confirmText' => 'Ya, Hapus',
    'loadingText' => 'Menghapus…',
])

<div class="modal-overlay" :class="{ 'open': $wire.{{ $show }} }" x-show="$wire.{{ $show }}" x-cloak
    x-on:keydown.escape.window="$wire.{{ $close }}()"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div class="modal-box !max-w-sm" x-on:click.outside="$wire.{{ $close }}()">
        <div class="p-6 text-center">
            <div class="w-12 h-12 rounded-full mx-auto flex items-center justify-center mb-4" style="background:rgba(255,46,23,.12)">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--red)">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                    <path d="M10 11v6" />
                    <path d="M14 11v6" />
                    <path d="M9 6V4h6v2" />
                </svg>
            </div>
            <h3 class="font-display font-semibold text-lg">{{ $title }}</h3>
            <p class="text-sm mt-1.5" style="color:var(--slate)">{{ $message }}</p>
            <div class="flex justify-center gap-2 mt-6">
                <button type="button" wire:click="{{ $close }}" class="btn btn-ghost">Batal</button>
                <button type="button" wire:click="{{ $action }}" class="btn btn-accent">
                    <span wire:loading.remove wire:target="{{ $action }}" style="display:flex" class="flex items-center gap-1.5">
                        <livewire:elements.icons.trash-2 class="w-4 h-4" />
                        {{ $confirmText }}
                    </span>
                    <span wire:loading wire:target="{{ $action }}" style="display:none">{{ $loadingText }}</span>
                </button>
            </div>
        </div>
    </div>
</div>