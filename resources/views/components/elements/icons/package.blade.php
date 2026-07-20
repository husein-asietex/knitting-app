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
    class="lucide lucide-package-icon lucide-package {{ $class }}" style="{{ $style }}">
    <path
        d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" />
    <path d="M12 22V12" />
    <polyline points="3.29 7 12 12 20.71 7" />
    <path d="m7.5 4.27 9 5.15" />
</svg>
