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
    class="lucide lucide-chevron-left-icon lucide-chevron-left {{ $class }}">
    <path d="m15 18-6-6 6-6" />
</svg>
