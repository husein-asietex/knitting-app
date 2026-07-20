<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div x-data="{
    show: false,
    type: 'success',
    message: '',
    timer: null,
}"
    x-on:notify.window="
        message = $event.detail.message;
        type    = $event.detail.type ?? 'success';
        show    = true;
        clearTimeout(timer);
        timer = setTimeout(() => show = false, 3000);
    "
    x-show="show" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak
    class="fixed bottom-5 right-5 z-50 flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-sm font-medium"
    :style="type === 'success'
        ?
        'background:var(--navy);color:#fff' :
        'background:var(--red);color:#fff'">
    <div x-show="type === 'success'">
        <livewire:elements.icons.circle-check class="w-4 h-4 shrink-0" />
    </div>
    <div x-show="type === 'error'">
        <livewire:elements.icons.alert-circle class="w-4 h-4 shrink-0" />
    </div>
    <span x-text="message"></span>
    <button type="button" x-on:click="show = false" class="ml-1 opacity-70 hover:opacity-100 cursor-pointer">
        <livewire:elements.icons.x class="w-3.5 h-3.5" />
    </button>
</div>
