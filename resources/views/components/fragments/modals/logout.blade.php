<?php

use Livewire\Component;

new class extends Component {
    public function logout(): void
    {
        $logId = session('login_log_id');

        if ($logId) {
            LoginLog::where('id', $logId)
                ->whereNull('logout_at')
                ->update([
                    'logout_at' => now(),
                ]);
        }

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect(route('login'), navigate: true);
    }
};
?>

<div class="modal-overlay" :class="{ 'open': confirmLogout }" x-show="confirmLogout" x-cloak
    x-on:keydown.escape.window="confirmLogout = false">
    <div class="modal-box p-6" x-on:click.outside="confirmLogout = false">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                style="background:var(--red-light)">
                <livewire:elements.icons.log-out class="w-5 h-5" style="color:var(--red)" />
            </div>
            <div>
                <h3 class="font-display font-semibold text-base">Keluar dari akun?</h3>
                <p class="text-sm" style="color:var(--slate)">Anda perlu login kembali untuk mengakses Knitting App.
                </p>
            </div>
        </div>
        <div class="flex gap-2 justify-end mt-5">
            <button type="button" x-on:click="confirmLogout = false" class="btn btn-ghost">
                Batal
            </button>
            <button type="button" wire:click="logout" class="btn" style="background:var(--red); color:#fff;">
                <livewire:elements.icons.log-out class="w-4 h-4" />
                Ya, Keluar
            </button>
        </div>
    </div>
</div>
