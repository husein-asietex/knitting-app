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
    class="lucide lucide-search-icon lucide-search {{ $class }}" style="{{ $style }}">
    <path d="m21 21-4.34-4.34" />
    <circle cx="11" cy="11" r="8" />
</svg>
