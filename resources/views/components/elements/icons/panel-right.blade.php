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

<svg xmlns="http://www.w3.org/2000/svg"  width="{{ $size }}" height="{{ $size }}"
    viewBox="0 0 24 24" fill="none" stroke="currentColor" 
    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" 
    class="lucide lucide-panel-right-icon lucide-panel-right {{ $class }}">
        <rect width="18" height="18" x="3" y="3" rx="2"/>
        <path d="M15 3v18"/>
</svg>
