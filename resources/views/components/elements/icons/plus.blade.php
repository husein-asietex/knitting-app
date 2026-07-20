<?php

use Livewire\Component;

new class extends Component {
    public int $size = 16;
    public string $class = '';

    public function mount(int $size = 16, string $class = '')
    {
        $this->size = $size;
        $this->class = $class;
    }
};
?>
<svg xmlns="http://www.w3.org/2000/svg" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24"
    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
    class="lucide lucide-plus-icon lucide-plus {{ $class }}">
    <path d="M5 12h14" />
    <path d="M12 5v14" />
</svg>
