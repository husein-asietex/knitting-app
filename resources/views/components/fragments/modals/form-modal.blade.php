@props([
    'show' => 'showModal',
    'close' => 'closeModal',
    'submit' => 'save',
    'title' => null,
    'maxWidth' => null,
    'loadingText' => 'Menyimpan…',
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
    <div class="modal-box flex flex-col {{ $maxWidth }}" style="max-height:90vh" x-on:click.outside="$wire.{{ $close }}()">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 shrink-0" style="border-bottom:1px solid var(--border)">
            <h3 class="font-display font-semibold text-lg">
                {{ $title }}
            </h3>
            <button type="button" wire:click="{{ $close }}" class="btn btn-ghost !p-1.5">
                <livewire:elements.icons.x class="w-3 h-3" />
            </button>
        </div>

        {{-- Form --}}
        <form wire:submit="{{ $submit }}" class="flex flex-col min-h-0 flex-1">
            <div class="p-6 space-y-4 overflow-y-auto flex-1">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-end gap-2 px-6 py-4 shrink-0" style="border-top:1px solid var(--border)">
                @isset($footer)
                    {{ $footer }}
                @else
                    <button type="button" wire:click="{{ $close }}" class="btn btn-ghost">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading.remove wire:target="{{ $submit }}" style="display:flex" class="flex items-center gap-2">
                            <livewire:elements.icons.save class="w-4 h-4" />
                            Simpan
                        </span>
                        <span wire:loading wire:target="{{ $submit }}" style="display:none">{{ $loadingText }}</span>
                    </button>
                @endisset
            </div>
        </form>

    </div>
</div>