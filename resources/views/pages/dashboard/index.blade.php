<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Auth;

// dd(auth()->user());
new #[Layout('layouts::app')] #[Title('Dashboard')] class extends Component
{

    
    public function mount()
    {
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

</main>

@script
    <script>
    </script>
@endscript
