<?php

use App\Models\Sections;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Illuminate\Validation\Rule;



new #[Layout('layouts::app')] #[Title('Sections')] class extends Component{
    use WithPagination, WithoutUrlPagination;

    /* ─── Filters ─── */
    public string $search = '';
    public string $sectionFilter = '';
    public int $perPage = 10;

    /* ─── Modal state ─── */
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deleteId = null;

    /* ─── Form fields ─── */
    public string $name = '';

    public function mount(){
       $this->dispatch('page-title', title: 'Sections', subtitle: 'Data untuk Section');
    }

    /* ─── Data ─── */
    public function with(): array
    {
        $sections = Sections::query()
            ->when($this->search, fn($q) => $q->where('name', 'ILIKE', "%{$this->search}%")
                ->orwhere('description', 'ILIKE', "%{$this->search}%"))
            ->when($this->sectionFilter, fn($q) => $q->where('id', $this->sectionFilter))
            ->orderBy('id')
            ->paginate($this->perPage);

        return [
            'sections'  => $sections,
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
            'description' => 'nullable|string|max:100',
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description
        ];

        if ($this->editingId) {
            Sections::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', type: 'success', message: 'Section berhasil diperbarui.');
        } else {
            Sections::create($data);
            $this->dispatch('notify', type: 'success', message: 'Section baru berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    /* ─── Edit ─── */
    public function edit(int $id): void
    {
        $section = Sections::findOrFail($id);
        $this->editingId = $id;
        $this->name = $section->name;
        $this->resetValidation();
        $this->showModal = true;
    }
    
    /* ─── Delete ─── */
    public function delete(): void
    {
        if (!$this->deleteId) {
            return;
        }

        Sections::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->dispatch('notify', type: 'success', message: 'Section berhasil dihapus.');
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
        $this->resetValidation();
    }
    
    /* ─── Message ─── */
    protected function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'description.required' => 'Deskripsi wajib diisi.',
        ];
    }

};

?>

<main class="flex-1 overflow-y-auto p-4 sm:p-6">

    {{-- ─── Header ─── --}}
    <div class="flex items-center justify-end gap-3 mb-5 anim-fade-up">
        
        <button wire:click="create" class="btn btn-accent self-start sm:self-auto">
            <livewire:elements.icons.plus class="w-4 h-4" />
            Tambah Section
        </button>
    </div>

    {{-- ─── Filter bar ─── --}}
    <div class="card p-4 mb-4 anim-fade-up" style="animation-delay:.06s">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="relative sm:col-span-2 lg:col-span-4">
                <livewire:elements.icons.search class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none w-4 h-4 text-slate" />
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama team..."
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
                        <th>Description</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sections as $section)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-[11px] font-bold shrink-0"
                                    style="background:var(--navy)">{{ $section->initials() }}</div>
                                <span class="font-medium">{{ $section->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="font-medium">{{ $section->description }}</span>
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1.5">
                                <button class="btn btn-ghost !py-1.5 !px-2.5" title="Edit"
                                wire:click="edit({{ $section->id }})">
                                    <livewire:elements.icons.edit class="w-4 h-4" />
                                </button>
                                <button class="btn btn-ghost !py-1.5 !px-2.5" style="color:var(--red)" title="Hapus"
                                    wire:click="confirmDelete({{ $section->id }})">
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
        {{ $sections->links() }}

    </div>
    
    {{-- ─── Modal Tambah / Edit ─── --}}
    @props(['editingId' => null])

    <x-fragments.modals.form-modal
        :title="$editingId ? 'Edit Section' : 'Tambah Section Baru'"
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
        
        {{-- Description --}}
        <div>
            <label class="label">Description</label>
            <input wire:model="description" type="text" class="input @error('description') !border-red-400 @enderror" placeholder="Masukkan data..." />
            @error('description')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

    </x-fragments.modals.form-modal>
    
    {{-- ─── Modal Confirm Delete ─── --}}
    <x-fragments.modals.delete-modal />

</main>
