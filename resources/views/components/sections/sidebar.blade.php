<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<aside class="shrink-0 hidden lg:flex flex-col bg-bg transition-all duration-300 overflow-hidden" x-cloak
    :class="openDesktopSidebar ? 'w-64' : 'w-0'"
    :style="openDesktopSidebar ? 'border-right:1px solid var(--border)' : 'border-right:0px solid var(--border)'">
    <div class="h-full flex flex-col w-64">
        <div class="px-5 py-5 flex items-center gap-2" style="">
            <img src="{{ asset('android-chrome-192x192.png') }}" class="w-6 h-6 object-contain rounded-lg"
                alt="Asietex" />
            <div>
                <p class="font-display font-semibold text-base leading-none">Knitting App</p>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto space-y-5 px-3 py-4">

            @can('access-dashboard')
                <div class="mt-0">
                    <p class="px-3 text-[11px] font-bold uppercase tracking-wider mb-1.5" style="color:var(--slate)">
                        Summary
                    </p>
                    <a href="{{ route('dashboard') }}" wire:navigate.hover wire:current="active" class="sidebar-link">
                        <livewire:elements.icons.layout-dashboard class="w-4 h-4" />
                        <span>Dashboard</span>
                    </a>
                </div>
                <div class="mt-0">
                    <p class="px-3 text-[11px] font-bold uppercase tracking-wider mb-1.5" style="color:var(--slate)">
                        Master Data</p>
                    <a href="{{ route('shifts') }}" wire:navigate.hover wire:current="active" class="sidebar-link">
                        <livewire:elements.icons.package class="w-4 h-4" />
                        <span>Shifts</span>
                    </a>
                    <a href="{{ route('teams') }}" wire:navigate.hover wire:current="active" class="sidebar-link">
                        <livewire:elements.icons.package class="w-4 h-4" />
                        <span>Teams</span>
                    </a>
                    <a href="{{ route('sections') }}" wire:navigate.hover wire:current="active" class="sidebar-link">
                        <livewire:elements.icons.package class="w-4 h-4" />
                        <span>Sections</span>
                    </a>
                    <a href="{{ route('users') }}" wire:navigate.hover wire:current="active" class="sidebar-link">
                        <livewire:elements.icons.package class="w-4 h-4" />
                        <span>Users</span>
                    </a>
                    <a href="{{ route('machines') }}" wire:navigate.hover wire:current="active" class="sidebar-link">
                        <livewire:elements.icons.package class="w-4 h-4" />
                        <span>Machines</span>
                    </a>
                    <a href="{{ route('machine-operators') }}" wire:navigate.hover wire:current="active" class="sidebar-link">
                        <livewire:elements.icons.package class="w-4 h-4" />
                        <span>Machine Operators</span>
                    </a>
                </div>
            @endcan

        </nav>

        <div class="p-3" style="">
            <button type="button" x-on:click="confirmLogout = true" class="sidebar-link w-full text-left">
                <livewire:elements.icons.log-out class="w-4 h-4" />
                <span>Keluar</span>
            </button>
        </div>
    </div>
</aside>
