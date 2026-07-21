<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Machines;
use App\Models\Shifts;
use App\Models\Teams;
use App\Models\Sections;
use App\Models\MachineOperators;

new #[Layout('layouts::app')] #[Title('Dashboard')] class extends Component
{
    public function mount()
    {
    }

    public function with(): array
    {
        return [
            'menuCards' => [
                [
                    'label' => 'Users',
                    'count' => User::count(),
                    'route' => 'users',
                    'color' => 'var(--navy)',
                    'icon'  => 'users',
                ],
                [
                    'label' => 'Machines',
                    'count' => Machines::count(),
                    'route' => 'machines',
                    'color' => 'var(--teal, #0d9488)',
                    'icon'  => 'cog',
                ],
                [
                    'label' => 'Machine Operators',
                    'count' => MachineOperators::count(),
                    'route' => 'machine-operators',
                    'color' => 'var(--purple, #7c3aed)',
                    'icon'  => 'user-check',
                ],
                [
                    'label' => 'Sections',
                    'count' => Sections::count(),
                    'route' => 'sections',
                    'color' => 'var(--amber, #d97706)',
                    'icon'  => 'layout-grid',
                ],
                [
                    'label' => 'Teams',
                    'count' => Teams::count(),
                    'route' => 'teams',
                    'color' => 'var(--blue, #2563eb)',
                    'icon'  => 'users-2',
                ],
                [
                    'label' => 'Shifts',
                    'count' => Shifts::count(),
                    'route' => 'shifts',
                    'color' => 'var(--rose, #e11d48)',
                    'icon'  => 'clock',
                ],
            ],
        ];
    }
};
?>

<main class="flex-1 overflow-y-auto p-4 sm:p-6">

    <div class="card p-4 mb-5 anim-fade-up">
        <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-4">
            <div>
                <p class="text-xs uppercase tracking-wide font-semibold" style="color:var(--slate)">
                    Filter Data Dashboard
                </p>
                <h2 class="font-display font-semibold text-lg mt-1">
                    Juli 2026
                </h2>
                <p class="text-xs mt-1" style="color:var(--slate)">
                    Statistik, status mesin, report terbaru, dan chart <br> akan mengikuti periode ini.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 w-full xl:w-auto">
                <div>
                    <label class="label">Periode</label>
                    <select class="input dashboard-select">
                        <option>Hari Ini</option>
                        <option>Minggu Ini</option>
                        <option selected>Bulan Ini</option>
                        <option>Tahun Ini</option>
                        <option>Range Tanggal</option>
                    </select>
                </div>

                <div>
                    <label class="label">Dari Tanggal</label>
                    <input type="date" class="input dashboard-select" value="2026-07-01">
                </div>

                <div>
                    <label class="label">Sampai Tanggal</label>
                    <input type="date" class="input dashboard-select" value="2026-07-31">
                </div>

                <div class="flex items-end">
                    <button type="button" class="btn btn-ghost w-full justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 12a9 9 0 1 0 3-6.7" />
                            <path d="M3 4v5h5" />
                        </svg>
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Menu Cards ─── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 anim-fade-up" style="animation-delay:.06s">
        @foreach ($menuCards as $menu)
            <a href="{{ route($menu['route']) }}" wire:navigate class="card p-4 flex items-center gap-3 hover:shadow-md transition">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background:{{ $menu['color'] }}1A">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                        stroke="{{ $menu['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        @switch($menu['icon'])
                            @case('users')
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                @break
                            @case('cog')
                                <circle cx="12" cy="12" r="3" />
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                                @break
                            @case('user-check')
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="8.5" cy="7" r="4" />
                                <path d="m17 11 2 2 4-4" />
                                @break
                            @case('layout-grid')
                                <rect x="3" y="3" width="7" height="7" rx="1" />
                                <rect x="14" y="3" width="7" height="7" rx="1" />
                                <rect x="14" y="14" width="7" height="7" rx="1" />
                                <rect x="3" y="14" width="7" height="7" rx="1" />
                                @break
                            @case('users-2')
                                <path d="M14 19a6 6 0 0 0-12 0" />
                                <circle cx="8" cy="9" r="4" />
                                <path d="M22 19a6 6 0 0 0-6-6 4 4 0 1 0 0-8" />
                                @break
                            @case('clock')
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                                @break
                        @endswitch
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium truncate" style="color:var(--slate)">{{ $menu['label'] }}</p>
                    <p class="font-display font-semibold text-xl">{{ $menu['count'] }}</p>
                </div>
            </a>
        @endforeach
    </div>

</main>

@script
    <script>
    </script>
@endscript