<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div id="mobile-sidebar" :class="openMobileSidebar ? 'open' : ''">
    <div class="h-full flex flex-col bg-bg">
        <div class="px-5 py-4 flex items-center gap-3" style="">
            <img src="{{ asset('android-chrome-192x192.png') }}" class="w-5 h-5 object-contain rounded-lg"
                alt="Asietex" />
            <div>
                <p class="font-display font-semibold text-base leading-none">Knitting App</p>
                <!-- <p class="text-[11px]" style="color:var(--slate)">Asietex Sinar Indopratama</p> -->
            </div>
        </div>
        <nav class="flex-1 overflow-y-auto space-y-4 px-3 py-4">

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
                        Production</p>
                    <a href="{{ route('knitting1') }}" wire:navigate.hover wire:current="active" class="sidebar-link">
                        <livewire:elements.icons.package class="w-4 h-4" />
                        <span>Knitting 1</span>
                    </a>
                </div>
                <div class="mt-0">
                    <p class="px-3 text-[11px] font-bold uppercase tracking-wider mb-1.5" style="color:var(--slate)">
                        Master Data</p>
                    <a href="{{ route('users') }}" wire:navigate.hover wire:current="active" class="sidebar-link">
                        <livewire:elements.icons.package class="w-4 h-4" />
                        <span>Users</span>
                    </a>
                    <a href="{{ route('machines') }}" wire:navigate.hover wire:current="active" class="sidebar-link">
                        <livewire:elements.icons.package class="w-4 h-4" />
                        <span>Machines</span>
                    </a>
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
</div>
