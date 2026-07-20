<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts::app')] #[Title('Knitting 1')] class extends Component
{

    public function mount()
    {
        $this->dispatch('page-title', title: 'Knitting 1', subtitle: 'Data master untuk produksi knitting 1');
    }
    
};
?>

<main class="flex-1 overflow-y-auto p-4 sm:p-6">
    <p>
        Knitting 1
    </p>
</main>

@script
    <script>
    </script>
@endscript
