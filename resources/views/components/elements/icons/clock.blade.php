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
    class="lucide lucide-clock-icon lucide-clock {{ $class }}" style="{{ $style }}">
    <circle cx="12" cy="12" r="10" />
    <path d="M12 6v6l4 2" />
</svg>
