<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

new #[Layout('layouts::auth')] #[Title('Login')] class extends Component {
    public string $username = '';
    public string $password = '';
    public string $loginError = '';

    public function login()
    {
        $this->loginError = '';

        $credentials = $this->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            request()->session()->regenerate();

            $user = Auth::user();

            $redirectTo = match (true) {
                in_array($user->role, ['superadmin', 'admin']) => route('dashboard'),
                default => route('login'),
            };

            $this->redirectIntended(default: $redirectTo, navigate: true);
            return;
        }

        $this->loginError = 'Username atau password salah.';
    }
};
?>

<div x-cloak>
    <div class="absolute top-4 right-4">
        <button x-on:click="toggleDarkMode()" class="btn !px-2.5 !py-2"
            style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);color:#fff">S
        </button>
    </div>

    <div class="w-full max-w-4xl rounded-2xl overflow-hidden anim-fade-up login-card">

        <!-- Right: form -->
        <div class="p-8 sm:p-10 flex flex-col justify-center" style="background:var(--surface)">
            <div class="flex items-center gap-3 mb-6">
                <img src="{{ asset('android-chrome-512x512.png') }}" class="w-9 h-9 object-contain"
                    alt="Asietex logo" />
                <div>
                    <p class="font-display font-bold">Knitting</p>
                    <p class="text-xs" style="color:var(--slate)">PT. Asietex Sinar Indopratama</p>
                </div>
            </div>

            <h1 class="font-display text-2xl font-bold">Selamat datang kembali</h1>
            <p class="text-sm mt-1.5" style="color:var(--slate)">
                Masuk untuk menginput laporan produksi shift Anda.
            </p>

            <form wire:submit="login" class="mt-7 space-y-4">
                <div>
                    <label class="label">Username</label>
                    <input type="text" wire:model="username" class="input" placeholder="contoh: hendra.w"
                        required />
                    @error('username')
                        <p class="text-xs mt-1" style="color:var(--red)">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ showPw: false }">
                    <label class="label">Password</label>
                    <div class="relative">
                        <input :type="showPw ? 'text' : 'password'" wire:model="password" class="input pr-10"
                            placeholder="••••••••" required />
                        <button type="button" x-on:click="showPw = !showPw"
                            class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer" style="color:var(--slate)">
                            <div x-show="showPw">
                                Mata
                            </div>
                            <div x-show="!showPw">
                                Mata
                            </div>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs mt-1" style="color:var(--red)">{{ $message }}</p>
                    @enderror
                </div>

                @if ($loginError)
                    <p class="text-xs font-medium px-3 py-2 rounded-lg"
                        style="color:var(--red);background:var(--red-light)">
                        {{ $loginError }}
                    </p>
                @endif

                <button type="submit" class="btn btn-primary w-full justify-center py-2.5 mt-2"
                    wire:loading.attr="disabled" wire:target="login">
                    <span class="flex items-center justify-center gap-2">
                        <svg wire:loading.remove wire:target="login" wire:key="icon-idle"
                            xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" style="display:inline-block; flex-shrink:0;">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                            <polyline points="10 17 15 12 10 7" />
                            <line x1="15" y1="12" x2="3" y2="12" />
                        </svg>

                        <svg wire:loading wire:target="login" wire:key="icon-loading"
                            xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            style="display:none; flex-shrink:0; animation: spin 1s linear infinite;">
                            <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                        </svg>

                        <span wire:loading.remove wire:target="login" wire:key="text-idle">Masuk</span>
                        <span wire:loading wire:target="login" wire:key="text-loading"
                            style="display:none;">Memproses...</span>
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .rail {
        background:
            radial-gradient(circle at 20% 25%, rgba(255, 46, 23, .2), transparent 45%),
            radial-gradient(circle at 80% 75%, rgba(16, 38, 148, .5), transparent 45%),
            linear-gradient(160deg, #0B1B5C 0%, #0A0D14 100%);
    }

    .login-card {
        background: var(--surface);
        border: 1px solid var(--border);
        box-shadow: 0 32px 80px rgba(0, 0, 0, .35);
    }

    .input-light {
        background: var(--surface-2);
        border-color: var(--border);
        color: var(--ink);
    }

    .input-light:focus {
        border-color: var(--navy);
        box-shadow: 0 0 0 3px rgba(16, 38, 148, .12);
    }
</style>
