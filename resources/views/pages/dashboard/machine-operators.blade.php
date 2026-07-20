<?php

use App\Models\MachineOperators;
use App\Models\Teams;
use App\Models\Shifts;
use App\Models\Sections;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Illuminate\Validation\Rule;



new #[Layout('layouts::app')] #[Title('Machine Operators')] class extends Component{
    use WithPagination, WithoutUrlPagination;

    /* ─── Filters ─── */
    public string $search = '';
    public string $sectionFilter = '';
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
    public ?string $position = '';
    public ?int $section_id = null;
    public ?int $team_id = null;
    public ?int $shift_id = null;

    public function mount(){
       $this->dispatch('page-title', title: 'Machine Operators', subtitle: 'Data untuk Machine Operators');
    }

    /* ─── Data ─── */
    public function with(): array
    {
        $machineOperators = MachineOperators::query()
            ->with(['team', 'shift', 'section'])
            ->when($this->search, fn($q) => $q->where('name', 'ILIKE', "%{$this->search}%"))
            ->when($this->sectionFilter, fn($q) => $q->where('section_id', $this->sectionFilter))
            ->when($this->teamFilter, fn($q) => $q->where('team_id', $this->teamFilter))
            ->when($this->shiftFilter, fn($q) => $q->where('shift_id', $this->shiftFilter))
            ->orderBy('name')
            ->paginate($this->perPage);

        return [
            'machineOperators'  => $machineOperators,
            'sections' => Sections::orderBy('id')->get(),
            'teams'  => Teams::orderBy('id')->get(),
            'shifts' => Shifts::orderBy('id')->get(),
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
            'position' => 'required',
            'section_id' => 'required|exists:sections,id',
            'team_id' => 'required|exists:teams,id',
            'shift_id' => 'required|exists:shifts,id',
        ]);

        $data = [
            'name' => $this->name,
            'position' => $this->position ?: null,
            'section_id' => $this->section_id,
            'team_id' => $this->team_id,
            'shift_id' => $this->shift_id,
        ];

        if ($this->editingId) {
            MachineOperators::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', type: 'success', message: 'Machine Operator berhasil diperbarui.');
        } else {
            MachineOperators::create($data);
            $this->dispatch('notify', type: 'success', message: 'Machine Operator baru berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    /* ─── Edit ─── */
    public function edit(int $id): void
    {
        $machineOperator = MachineOperators::findOrFail($id);
        $this->editingId = $id;
        $this->name = $machineOperator->name;
        $this->position = $machineOperator->position ?? '';
        $this->section_id = $machineOperator->section_id;
        $this->team_id = $machineOperator->team_id;
        $this->shift_id = $machineOperator->shift_id;
        $this->resetValidation();
        $this->showModal = true;
    }
    
    /* ─── Delete ─── */
    public function delete(): void
    {
        if (!$this->deleteId) {
            return;
        }

        MachineOperators::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->dispatch('notify', type: 'success', message: 'Machine Operators berhasil dihapus.');
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
        $this->position = null;
        $this->section_id = null;
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
            'position.max' => 'Jabatan maksimal 100 karakter.',
            'section_id.required' => 'Section wajib dipilih.',
            'team_id.required' => 'Team wajib dipilih.',
            'shift_id.required' => 'Shift wajib dipilih.',
        ];
    }

};

?>

<main class="flex-1 overflow-y-auto p-4 sm:p-6">

    {{-- ─── Header ─── --}}
    <div class="flex items-center justify-end gap-3 mb-5 anim-fade-up">
        
        <button wire:click="create" class="btn btn-accent self-start sm:self-auto">
            <livewire:elements.icons.plus class="w-4 h-4" />
            Tambah Machine Operator
        </button>
    </div>

    {{-- ─── Filter bar ─── --}}
    <div class="card p-4 mb-4 anim-fade-up" style="animation-delay:.06s">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="relative sm:col-span-2 lg:col-span-1">
                <livewire:elements.icons.search class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none w-4 h-4 text-slate" />
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama machine operator..."
                    class="input pl-9" />
            </div>
            <select wire:model.live="sectionFilter" class="input sm:col-span-2 lg:col-span-1">
                <option value="" selected>Semua Section</option>                
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
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
                        <th>Position</th>
                        <th>Section</th>
                        <th>Team</th>
                        <th>Shift</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($machineOperators as $machineOperator)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-[11px] font-bold shrink-0"
                                    style="background:var(--navy)">{{ $machineOperator->initials() }}</div>
                                <span class="font-medium">{{ $machineOperator->name }}</span>
                            </div>
                        </td>
                        <td><span class="text-sm" style="color:var(--slate)">{{ $machineOperator->position }}</span></td>
                        <td>{{ $machineOperator->section->name }}</td>
                        <td>{{ $machineOperator->team->name }}</td>
                        <td>{{ $machineOperator->shift->name }}</td>
                        <td>
                            <div class="flex items-center justify-end gap-1.5">
                                <button class="btn btn-ghost !py-1.5 !px-2.5" title="Edit"
                                wire:click="edit({{ $machineOperator->id }})">
                                    <livewire:elements.icons.edit class="w-4 h-4" />
                                </button>
                                <button class="btn btn-ghost !py-1.5 !px-2.5" style="color:var(--red)" title="Hapus"
                                    wire:click="confirmDelete({{ $machineOperator->id }})">
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
        {{ $machineOperators->links() }}

    </div>
    
    {{-- ─── Modal Tambah / Edit ─── --}}
    @props(['editingId' => null, 'roles' => [], 'teams' => [], 'shifts' => []])

    <x-fragments.modals.form-modal
        :title="$editingId ? 'Edit Machine Operator' : 'Tambah Machine Operator'"
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

        {{-- Section --}}
        <div>
            <label class="label">Section <span style="color:var(--red)">*</span></label>
            <select wire:model="section_id" class="input @error('section_id') !border-red-400 @enderror">
                <option value="">— Pilih Section —</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>
            @error('section_id')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

        {{-- Section --}}
        <div>
            <label class="label">Section <span style="color:var(--red)">*</span></label>
            <select wire:model="section_id" class="input @error('section_id') !border-red-400 @enderror">
                <option value="">— Pilih Section —</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>
            @error('section_id')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>
        
        {{-- Team --}}
        <div>
            <label class="label">Team <span style="color:var(--red)">*</span></label>
            <select wire:model="team_id" class="input @error('team_id') !border-red-400 @enderror">
                <option value="">— Pilih Team —</option>
                <option value="2">Admin</option>
                <option value="3">User</option>
                
            </select>
            @error('team_id')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

        {{-- Shift --}}
        <div>
            <label class="label">Shift <span style="color:var(--red)">*</span></label>
            <select wire:model="shift_id" class="input @error('shift_id') !border-red-400 @enderror">
                <option value="">— Pilih Shift —</option>
                @foreach ($shifts as $shift)
                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                @endforeach
            </select>
            @error('shift_id')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

    </x-fragments.modals.form-modal>
    
    {{-- ─── Modal Confirm Delete ─── --}}
    <x-fragments.modals.delete-modal />

</main>
