<?php

use App\Models\Shifts;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Illuminate\Validation\Rule;



new #[Layout('layouts::app')] #[Title('Shifts')] class extends Component{
    use WithPagination, WithoutUrlPagination;

    /* ─── Filters ─── */
    public string $search = '';
    public string $shiftFilter = '';
    public int $perPage = 10;

    /* ─── Modal state ─── */
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deleteId = null;

    /* ─── Form fields ─── */
    public string $name = '';
    public string $start_at = '';
    public string $finished_at = '';

    public function mount(){
       $this->dispatch('page-title', title: 'Shifts', subtitle: 'Data untuk Shift');
    }

    /* ─── Data ─── */
    public function with(): array
    {
        $shifts = Shifts::query()
            ->when($this->search, fn($q) => $q->where('name', 'ILIKE', "%{$this->search}%"))
            ->when($this->shiftFilter, fn($q) => $q->where('id', $this->shiftFilter))
            ->orderBy('id')
            ->paginate($this->perPage);

        return [
            'shifts'  => $shifts,
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
            'start_at' => [
                'required', 'string', 'max:100',
                Rule::unique('users', 'username')->ignore($this->editingId),
            ],
            'finished_at' => 'nullable|string|max:100',
        ]);

        $data = [
            'name' => $this->name,
            'start_at' => $this->start_at,
            'finished_at' => $this->finished_at,
        ];

        if ($this->editingId) {
            Shifts::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', type: 'success', message: 'Shift berhasil diperbarui.');
        } else {
            Shifts::create($data);
            $this->dispatch('notify', type: 'success', message: 'Shift baru berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    /* ─── Edit ─── */
    public function edit(int $id): void
    {
        $shift = Shifts::findOrFail($id);
        $this->editingId = $id;
        $this->name = $shift->name;
        $this->start_at = $shift->start_at;
        $this->finished_at = $shift->finished_at;
        $this->resetValidation();
        $this->showModal = true;
    }
    
    /* ─── Delete ─── */
    public function delete(): void
    {
        if (!$this->deleteId) {
            return;
        }

        Shifts::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->dispatch('notify', type: 'success', message: 'Shift berhasil dihapus.');
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
        $this->start_at = '';
        $this->finished_at = '';
        $this->resetValidation();
    }
    
    /* ─── Message ─── */
    protected function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'start_at.required' => 'Jam masuk wajib diisi.',
            'finished_at.required' => 'Jam pulang wajib diisi.',
        ];
    }

};

?>

<main class="flex-1 overflow-y-auto p-4 sm:p-6">

    {{-- ─── Header ─── --}}
    <div class="flex items-center justify-end gap-3 mb-5 anim-fade-up">
        
        <button wire:click="create" class="btn btn-accent self-start sm:self-auto">
            <livewire:elements.icons.plus class="w-4 h-4" />
            Tambah Shift
        </button>
    </div>

    {{-- ─── Filter bar ─── --}}
    <div class="card p-4 mb-4 anim-fade-up" style="animation-delay:.06s">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="relative sm:col-span-2 lg:col-span-4">
                <livewire:elements.icons.search class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none w-4 h-4 text-slate" />
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama shift..."
                    class="input pl-9" />
            </div>
        </div>

    </div>

    {{-- ─── Tabel ─── --}}
    <div class="card overflow-hidden anim-fade-up" style="animation-delay:.12s">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($shifts as $shift)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-[11px] font-bold shrink-0"
                                    style="background:var(--navy)">{{ $shift->initials() }}</div>
                                <span class="font-medium">{{ $shift->name }}</span>
                            </div>
                        </td>
                        <td>{{ $shift->start_at }}</td>
                        <td>{{ $shift->finished_at }}</td>
                        <td>
                            <div class="flex items-center justify-end gap-1.5">
                                <button class="btn btn-ghost !py-1.5 !px-2.5" title="Edit"
                                wire:click="edit({{ $shift->id }})">
                                    <livewire:elements.icons.edit class="w-4 h-4" />
                                </button>
                                <button class="btn btn-ghost !py-1.5 !px-2.5" style="color:var(--red)" title="Hapus"
                                    wire:click="confirmDelete({{ $shift->id }})">
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
        {{ $shifts->links() }}

    </div>
    
    {{-- ─── Modal Tambah / Edit ─── --}}
    @props(['editingId' => null])

    <x-fragments.modals.form-modal
        :title="$editingId ? 'Edit Shift' : 'Tambah Shift Baru'"
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

        {{-- Jam Masuk --}}
        <div>
            <label class="label">Jam Masuk<span style="color:var(--red)">*</span></label>
            <input wire:model="start_at" type="time" class="input @error('start_at') !border-red-400 @enderror" placeholder="Masukkan data..." />
            @error('start_at')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

        {{-- Jam Pulang --}}
        <div>
            <label class="label">Jam Pulang<span style="color:var(--red)">*</span></label>
            <input wire:model="finished_at" type="time" class="input @error('finished_at') !border-red-400 @enderror" placeholder="Masukkan data..." />
            @error('finished_at')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

    </x-fragments.modals.form-modal>
    
    {{-- ─── Modal Confirm Delete ─── --}}
    <x-fragments.modals.delete-modal />

</main>
