<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')]
class extends Component
{
    //
};
?>

<div>
    <div class="flex items-center justify-between w-full p-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Requisitos</h1>
            <p class="text-muted-foreground mt-1">Gerencie os requisitos do clube</p>
        </div>

        <button
            wire:click="$dispatch('open-requirement-modal')"
            class="cursor-pointer flex items-center bg-primary text-primary-foreground h-10 px-4 rounded-lg">
            <i data-lucide="chart-no-axes-column" class="mr-2 h-4 w-4"></i>
            <span>Novo Requisito</span>
        </button>
    </div>
    <div class="flex flex-col items-center justify-between w-full p-8 gap-4">
        <livewire:components.requirement.form/>
        <livewire:components.requirement.table/>
    </div>
</div>
