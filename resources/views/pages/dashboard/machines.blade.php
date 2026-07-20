<?php

use App\Models\Machines;
use App\Models\Sections;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Illuminate\Validation\Rule;



new #[Layout('layouts::app')] #[Title('Machines')] class extends Component{
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
    public string $type = '';
    public string $brand = '';
    public int $gauge = 0;
    public int $feeder_count = 0;
    public float $cylinder_dia = 0;
    public int $section_id = 0;
    public bool $is_active = true;

    public function mount(){
       $this->dispatch('page-title', title: 'Machines', subtitle: 'Data untuk Machines');
    }

    /* ─── Data ─── */
    public function with(): array
    {
        $machines = Machines::query()
            ->with(['section'])
            ->when($this->search, fn($q) => $q->where('name', 'ILIKE', "%{$this->search}%"))
            ->when($this->sectionFilter, fn($q) => $q->where('section_id', $this->sectionFilter))
            ->orderBy('name')
            ->paginate($this->perPage);

        return [
            'machines'  => $machines,
            'sections' => Sections::orderBy('id')->get(),
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
            'type' => 'required',
            'brand' => 'required',
            'gauge' => 'required',
            'feeder_count' => 'required',
            'cylinder_dia' => 'required',
            'section_id' => 'required|exists:sections,id',
            'is_active' => 'required',
        ]);

        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'brand' => $this->brand,
            'gauge' => $this->gauge,
            'feeder_count' => $this->feeder_count,
            'cylinder_dia' => $this->cylinder_dia,
            'section_id' => $this->section_id,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            Machines::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', type: 'success', message: 'Machine berhasil diperbarui.');
        } else {
            Machines::create($data);
            $this->dispatch('notify', type: 'success', message: 'Machine baru berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->resetForm();
    }
    
    /* ─── Edit ─── */
    public function edit(int $id): void
    {
        $machine = Machines::findOrFail($id);
        $this->editingId = $id;
        $this->name = $machine->name;
        $this->type = $machine->type;
        $this->brand = $machine->brand;
        $this->gauge = $machine->gauge;
        $this->feeder_count = $machine->feeder_count;
        $this->cylinder_dia = $machine->cylinder_dia;
        $this->section_id = $machine->section_id;
        $this->is_active = $machine->is_active;
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
        $this->type = '';
        $this->brand = '';
        $this->gauge = 0;
        $this->feeder_count = 0;
        $this->cylinder_dia = 0;
        $this->section_id = 0;
        $this->is_active = true;
        $this->resetValidation();
    }
    
    /* ─── Message ─── */
    protected function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'type.required' => 'Tipe wajib diisi.',
            'brand.required' => 'Merek wajib diisi.',
            'gauge.required' => 'Gauge wajib diisi.',
            'feeder_count.required' => 'Jumlah feeder wajib diisi.',
            'feeder_count.integer' => 'Jumlah feeder harus berupa bilangan bulat.',
            'cylinder_dia.required' => 'Diameter silinder wajib diisi.',
            'cylinder_dia.numeric' => 'Diameter silinder harus berupa angka.',
            'section_id.required' => 'Section wajib dipilih.',
            'section_id.exists' => 'Section tidak ditemukan.',
        ];
    }

};

?>

<main class="flex-1 overflow-y-auto p-4 sm:p-6">

    {{-- ─── Header ─── --}}
    <div class="flex items-center justify-end gap-3 mb-5 anim-fade-up">
        
        <button wire:click="create" class="btn btn-accent self-start sm:self-auto">
            <livewire:elements.icons.plus class="w-4 h-4" />
            Tambah Machine
        </button>
    </div>

    {{-- ─── Filter bar ─── --}}
    <div class="card p-4 mb-4 anim-fade-up" style="animation-delay:.06s">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="relative sm:col-span-2 lg:col-span-3">
                <livewire:elements.icons.search class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none w-4 h-4 text-slate" />
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama user..."
                    class="input pl-9" />
            </div>
            <select wire:model.live="sectionFilter" class="input sm:col-span-2 lg:col-span-1">
                <option value="" selected>Semua Section</option>                
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
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
                        <th>Type</th>
                        <th>Brand</th>
                        <th>Gauge</th>
                        <th>Feeder Count</th>
                        <th>Cylinder Dia</th>
                        <th>Section</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($machines as $machine)
                    <tr>
                        <td>
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-[11px] font-bold shrink-0"
                                    style="background:var(--navy)">{{ $machine->initials() }}</div>
                                <span class="font-medium">{{ $machine->name }}</span>
                            </div>
                        </td>
                        <td>{{ $machine->type }}</td>
                        <td><span class="text-sm" style="color:var(--slate)">{{ $machine->brand }}</span></td>
                        <td>{{ $machine->gauge }}</td>
                        <td>{{ $machine->feeder_count }}</td>
                        <td>{{ $machine->cylinder_dia }}</td>
                        <td>{{ $machine->section->name }}</td>
                        <td>
                            @if($machine->is_active)
                            <span class="badge badge-success">Active</span>
                            @else
                            <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-1.5">
                                <button class="btn btn-ghost !py-1.5 !px-2.5" title="Edit"
                                wire:click="edit({{ $machine->id }})">
                                    <livewire:elements.icons.edit class="w-4 h-4" />
                                </button>
                                <button class="btn btn-ghost !py-1.5 !px-2.5" style="color:var(--red)" title="Hapus"
                                    wire:click="confirmDelete({{ $machine->id }})">
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
        {{ $machines->links() }}

    </div>
    
    {{-- ─── Modal Tambah / Edit ─── --}}
    @props(['editingId' => null, 'sections' => []])

    <x-fragments.modals.form-modal
        :title="$editingId ? 'Edit Machine' : 'Tambah Machine Baru'"
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

        {{-- Type --}}
        <div>
            <label class="label">Type<span style="color:var(--red)">*</span></label>
            <input wire:model="type" type="text" class="input @error('type') !border-red-400 @enderror" placeholder="Masukkan data..." />
            @error('type')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

        {{-- Brand --}}
        <div>
            <label class="label">Brand<span style="color:var(--red)">*</span></label>
            <input wire:model="brand" type="text" class="input @error('brand') !border-red-400 @enderror" placeholder="Masukkan data..." />
            @error('brand')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

        {{-- Gauge --}}
        <div>
            <label class="label">Gauge<span style="color:var(--red)">*</span></label>
            <input wire:model="gauge" type="text" class="input @error('gauge') !border-red-400 @enderror" placeholder="Masukkan data..." />
            @error('gauge')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

        {{-- Feeder Count --}}
        <div>
            <label class="label">Feeder Count<span style="color:var(--red)">*</span></label>
            <input wire:model="feeder_count" type="text" class="input @error('feeder_count') !border-red-400 @enderror" placeholder="Masukkan data..." />
            @error('feeder_count')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

        {{-- Cylinder Dia --}}
        <div>
            <label class="label">Cylinder Dia<span style="color:var(--red)">*</span></label>
            <input wire:model="cylinder_dia" type="text" class="input @error('cylinder_dia') !border-red-400 @enderror" placeholder="Masukkan data..." />
            @error('cylinder_dia')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

        {{-- Section --}}
        <div>
            <label class="label">Section<span style="color:var(--red)">*</span></label>
            <select wire:model="section_id" class="input @error('section_id') !border-red-400 @enderror">
                <option value="" selected>Pilih Section</option>
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

        {{-- Status --}}
        <div>
            <label class="label">Status<span style="color:var(--red)">*</span></label>
            <select wire:model="is_active" class="input @error('is_active') !border-red-400 @enderror">
                <option value="" selected>Pilih Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            @error('is_active')
                <p class="flex items-center gap-1 text-xs mt-1.5" style="color:var(--red)">
                    <livewire:elements.icons.info class="w-3 h-3" />{{ $message }}
                </p>
            @enderror
        </div>

    </x-fragments.modals.form-modal>
    
    {{-- ─── Modal Confirm Delete ─── --}}
    <x-fragments.modals.delete-modal />

</main>
