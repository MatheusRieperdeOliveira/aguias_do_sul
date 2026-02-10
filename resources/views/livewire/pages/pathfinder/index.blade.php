<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Services\PathfinderService;

new #[Layout('layouts.app')]
class extends Component {
//
};
?>

<div>
    <div class="flex items-center justify-between w-full p-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Desbravadores</h1>
            <p class="text-muted-foreground mt-1">Gerencie os membros do clube</p>
        </div>

        <button
            wire:click="$dispatch('open-pathfinder-modal')"
            class="cursor-pointer flex items-center bg-primary text-primary-foreground h-10 px-4 rounded-lg">
            <i data-lucide="user-plus" class="mr-2 h-4 w-4"></i>
            <span>Novo Cadastro</span>
        </button>
    </div>
    <div class="flex flex-col items-center justify-between w-full p-8 gap-4">
        <livewire:components.pathfinder.form/>
        <livewire:components.pathfinder.table/>
    </div>
</div>
