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
    class="lucide lucide-log-out-icon lucide-log-out {{ $class }}" style="{{ $style }}">
    <path d="m16 17 5-5-5-5" />
    <path d="M21 12H9" />
    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
</svg>
