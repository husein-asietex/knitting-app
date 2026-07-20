<?php
use Livewire\Component;
new class extends Component {
    public int $size = 16;
    public string $class = '';
    public string $style = '';
    public function mount(int $size = 16, string $class = '', string $style = '')
    {
        $this->size = $size;
        $this->class = $class;
        $this->style = $style;
    }
};
?>

<svg xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24"
    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
    class="lucide lucide-history-icon lucide-history {{ $class }}" style="{{ $style }}">
    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
    <path d="M3 3v5h5" />
    <path d="M12 7v5l4 2" />
</svg>
