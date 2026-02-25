<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.app')]
class extends Component {
    //
};
?>

<div>
    <div class="flex items-center justify-between w-full p-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Unidades</h1>
            <p class="text-muted-foreground mt-1">Gerencie as unidades do clube</p>
        </div>

        <button
            wire:click="$dispatch('open-unit-modal')"
            class="cursor-pointer flex items-center bg-primary text-primary-foreground h-10 px-4 rounded-lg">
            <i data-lucide="flag" class="mr-2 h-4 w-4"></i>
            <span>Nova unidade</span>
        </button>
    </div>
    <div class="flex flex-col items-center justify-between w-full p-8 gap-4">
        <livewire:components.unit.form />
        <livewire:components.unit.table />
    </div>
</div>
