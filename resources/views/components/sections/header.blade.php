<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<header class="h-16 shrink-0" style="background:var(--surface);border-bottom:1px solid var(--border)">
    <div class="flex items-center justify-between h-full px-4 sm:px-6">
        <div class="flex items-center gap-3">
            <button x-on:click="openMobileSidebar = true" class="hamburger-btn btn btn-ghost !px-2.5 cursor-pointer"
                style="border-color:var(--border)">
                <livewire:elements.icons.panel-right class="w-4 h-4" />
            </button>
            <div class="flex items-center gap-2">
                <div x-on:click="openDesktopSidebar = !openDesktopSidebar" class="panel-right-btn btn btn-ghost !px-2.5 cursor-pointer flex items-center">
                    <livewire:elements.icons.panel-right class="w-4 h-4" />
                </div>
                <p x-data="{ title: 'Dashboard' }" x-on:page-title.window="title = $event.detail.title" x-text="title"
                    class="flex items-center font-display font-semibold text-xl leading-tight mb-[1px]"></p>
                {{-- <p x-data="{ subtitle: 'Pantau performa produksi knitting hari ini' }" x-on:page-title.window="subtitle = $event.detail.subtitle ?? ''"
                    x-text="subtitle" class="text-xs hidden sm:block" style="color:var(--slate)"> --}}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2 sm:gap-3">
            @if (auth()->user()->shift_id || auth()->user()->team_id)
                <span class="badge badge-on hidden sm:inline-flex">
                    {{ auth()->user()->team?->name ?? (auth()->user()->team_name ?? '—') }} ·
                    {{ auth()->user()->shift?->name ?? (auth()->user()->shift_name ?? '—') }}

                    @if (auth()->user()->shift?->start_at && auth()->user()->shift?->finished_at)
                        ·
                        {{ auth()->user()->shift->start_at->format('H:i') }}
                        -
                        {{ auth()->user()->shift->finished_at->format('H:i') }}
                    @endif
                </span>
            @endif
            <button x-on:click="toggleDarkMode()" class="btn btn-ghost !px-2.5" title="Ganti tema">
                <livewire:elements.icons.sun-moon class="w-4 h-4" />
            </button>
            <div class="flex items-center gap-2 pl-2 sm:pl-3" style="border-left:1px solid var(--border)">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold"
                    style="background:var(--navy)">{{ auth()->user()->initials() }}</div>
                <div class="hidden md:block leading-tight">
                    <p class="text-sm font-semibold">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] uppercase" style="color:var(--slate)">{{ auth()->user()->position }}</p>
                </div>
            </div>
        </div>
    </div>
</header>
