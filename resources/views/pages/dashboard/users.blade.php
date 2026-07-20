<?php

use App\Models\User;
use App\Models\Roles;
use App\Models\Teams;
use App\Models\Shifts;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Illuminate\Validation\Rule;



new #[Layout('layouts::app')] #[Title('Users')] class extends Component{
    use WithPagination, WithoutUrlPagination;

    /* ─── Filters ─── */
    public string $search = '';
    public string $blockFilter = '';
    public string $teamFilter = '';
    public string $shiftFilter = '';
    public int $perPage = 10;

    /* ─── Modal state ─── */
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deleteId = null;

    /* ─── Form fields ─── */
    public string $name = '';
    public string $username = '';
    public ?string $position = '';
    public string $email = '';
    public string $password = '';
    public ?int $role_id = null;
    public ?int $team_id = null;
    public ?int $shift_id = null;

    public function mount(){
       $this->dispatch('page-title', title: 'Users', subtitle: 'Data untuk User');
    }

    /* ─── Data ─── */
    public function with(): array
    {
        $users = User::query()
            ->with(['team', 'shift'])
            ->when($this->search, fn($q) => $q->where('name', 'ILIKE', "%{$this->search}%"))
            ->when($this->teamFilter, fn($q) => $q->where('team_id', $this->teamFilter))
            ->when($this->shiftFilter, fn($q) => $q->where('shift_id', $this->shiftFilter))
            ->orderBy('name')
            ->paginate($this->perPage);

        // dd($users); // ← uncomment di sini jika ingin debug

        return [
            'users'  => $users,
            'teams'  => Teams::orderBy('id')->get(),
            'shifts' => Shifts::orderBy('id')->get(),
            'roles'  => Roles::orderBy('id')->get(),
        ];
    }

    /* ─── Modal open/reset ─── */
    public function create(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    /* ─── CRUD ─── */
    public function save(): void
    {
        $this->validate([
            'name' => 'required',
            'username' => [
                'required', 'string', 'max:100',
                Rule::unique('users', 'username')->ignore($this->editingId),
            ],
            'position' => 'nullable|string|max:100',
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($this->editingId),
            ],
            'password' => $this->editingId ? 'nullable|string|max:100' : 'required|string|max:100',
            'role_id' => 'required|exists:roles,id',
            'team_id' => 'required|exists:teams,id',
            'shift_id' => 'required|exists:shifts,id',
        ]);

        $data = [
            'name' => $this->name,
            'username' => $this->username,
            'position' => $this->position ?: null,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'role_id' => $this->role_id,
            'team_id' => $this->team_id,
            'shift_id' => $this->shift_id,
        ];

        if ($this->editingId) {
            User::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', type: 'success', message: 'User berhasil diperbarui.');
        } else {
            User::create($data);
            $this->dispatch('notify', type: 'success', message: 'User baru berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->resetForm();
    }
    
    /* ─── Edit ─── */
    public function edit(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editingId = $id;
        $this->name = $user->name;
        $this->position = $user->position ?? '';
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->team_id = $user->team_id;
        $this->shift_id = $user->shift_id;
        $this->resetValidation();
        $this->showModal = true;
    }
    
    /* ─── Delete ─── */
    public function delete(): void
    {
        if (!$this->deleteId) {
            return;
        }

        User::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->dispatch('notify', type: 'success', message: 'User berhasil dihapus.');
    }

    /* ─── Confirm Delete ─── */
    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }
    
    /* ─── Close Modal ─── */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }
    
    /* ─── Reset Form ─── */
    protected function resetForm(): void
    {
        $this->name = '';
        $this->username = '';
        $this->position = null;
        $this->email = '';
        $this->password = '';
        $this->role_id = null;
        $this->team_id = null;
        $this->shift_id = null;
        $this->resetValidation();
    }
    
    /* ─── Message ─── */
    protected function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.max' => 'Username maksimal 100 karakter.',
            'position.max' => 'Jabatan maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'email.max' => 'Email maksimal 255 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.max' => 'Password maksimal 100 karakter.',
            'role_id.required' => 'Role wajib dipilih.',
            'role_id.exists' => 'Role tidak ditemukan.',
            'team_id.required' => 'Regu wajib dipilih.',
            'team_id.exists' => 'Regu tidak ditemukan.',
            'shift_id.required' => 'Shift wajib dipilih.',
            'shift_id.exists' => 'Shift tidak ditemukan.',
        ];
    }

};

?>

<main class="flex-1 overflow-y-auto p-4 sm:p-6">

    {{-- ─── Header ─── --}}
    <div class="flex items-center justify-end gap-3 mb-5 anim-fade-up">
        
        <button wire:click="create" class="btn btn-accent self-start sm:self-auto">
            <livewire:elements.icons.plus class="w-4 h-4" />
            Tambah Operator
        </button>
    </div>

    {{-- ─── Filter bar ─── --}}
    <div class="card p-4 mb-4 anim-fade-up" style="animation-delay:.06s">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="relative sm:col-span-2 lg:col-span-1">
                <livewire:elements.icons.search class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none w-4 h-4 text-slate" />
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama user..."
                    class="input pl-9" />
            </div>
            <select wire:model.live="roleFilter" class="input sm:col-span-2 lg:col-span-1">
                <option value="" selected>Semua Role</option>                
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="teamFilter" class="input sm:col-span-2 lg:col-span-1">
                <option value="" selected>Semua Team</option>
                @foreach ($teams as $team)
                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="shiftFilter" class="input sm:col-span-2 lg:col-span-1">
                <option value="" selected>Semua Shift</option>
                @foreach ($shifts as $shift)
                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                @endforeach
            </select>
        </div>

    </div>

    {{-- ─── Tabel ─── --}}
    <div class="card overflow-hidden anim-fade-up" style="animation-delay:.12s">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Position</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Team</th>
                        <th>Shift</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-[11px] font-bold shrink-0"
                                    style="background:var(--navy)">{{ $user->initials() }}</div>
                                <span class="font-medium">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->username }}</td>
                        <td><span class="text-sm" style="color:var(--slate)">{{ $user->position }}</span></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->name }}</td>
                        <td>{{ $user->team->name }}</td>
                        <td>{{ $user->shift->name }}</td>
                        <td>
                            <div class="flex items-center justify-end gap-1.5">
                                <button class="btn btn-ghost !py-1.5 !px-2.5" title="Edit"
                                wire:click="edit({{ $user->id }})">
                                    <livewire:elements.icons.edit class="w-4 h-4" />
                                </button>
                                <button class="btn btn-ghost !py-1.5 !px-2.5" style="color:var(--red)" title="Hapus"
                                    wire:click="confirmDelete({{ $user->id }})">
                                    <livewire:elements.icons.trash-2 class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination sample --}}
        {{ $users->links() }}

    </div>
    
    {{-- ─── Modal Tambah / Edit ─── --}}
    @props(['editingId' => null, 'roles' => [], 'teams' => [], 'shifts' => []])

    <x-fragments.modals.form-modal
        :title="$editingId ? 'Edit User' : 'Tambah User Baru'"
        show="showModal"
        close="closeModal"
        submit="save"
    >
        {{-- Nama --}}
        <div>
            <label class="label">Name<span style="color:var(--red)">*</span></label>
            <input wire:model="name" type="text" class="input @error('name') !border-red-400 @enderror" placeholder="Masukkan data..." autofocus />
            @error('name')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

        {{-- Username --}}
        <div>
            <label class="label">Username<span style="color:var(--red)">*</span></label>
            <input wire:model="username" type="text" class="input @error('username') !border-red-400 @enderror" placeholder="Masukkan data..." />
            @error('username')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

        {{-- Position --}}
        <div>
            <label class="label">Position</label>
            <input wire:model="position" type="text" class="input @error('position') !border-red-400 @enderror" placeholder="Masukkan data..." />
            @error('position')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="label">Email<span style="color:var(--red)">*</span></label>
            <input wire:model="email" type="email" class="input @error('email') !border-red-400 @enderror" placeholder="Masukkan data..." />
            @error('email')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label class="label">Password<span style="color:var(--red)">*</span></label>
            <input wire:model="password" type="password" class="input @error('password') !border-red-400 @enderror" placeholder="Masukkan data..." />
            @error('password')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

        {{-- Role --}}
        <div>
            <label class="label">Role <span style="color:var(--red)">*</span></label>
            <select wire:model="role_id" class="input @error('role_id') !border-red-400 @enderror">
                <option value="">— Pilih Role —</option>
                <option value="2">Admin</option>
                <option value="3">User</option>
                
            </select>
            @error('role_id')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

        {{-- Team & Shift --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="label">Team <span style="color:var(--red)">*</span></label>
                <select wire:model="team_id" class="input @error('team_id') !border-red-400 @enderror">
                    <option value="">— Pilih Team —</option>
                    <option value="1">Team A</option>
                    <option value="2">Team B</option>
                    <option value="3">Team C</option>
                    <option value="4">Team D</option>
                    <option value="5">Team E</option>
                </select>
                @error('team_id')
                    <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                        <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                    </p>
                @enderror
            </div>
            <div>
                <label class="label">Shift <span style="color:var(--red)">*</span></label>
                <select wire:model="shift_id" class="input @error('shift_id') !border-red-400 @enderror">
                    <option value="">— Pilih shift —</option>
                    <option value="1">Shift 1 (Pagi)</option>
                    <option value="2">Shift 2 (Siang)</option>
                    <option value="3">Shift 3 (Malam)</option>
                </select>
                @error('shift_id')
                    <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                        <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </x-fragments.modals.form-modal>
    
    {{-- ─── Modal Confirm Delete ─── --}}
    <x-fragments.modals.delete-modal />

</main>
