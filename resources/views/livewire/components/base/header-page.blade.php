<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $title;
    public string $icon;
    public string $description;
    public string $event;
};
?>

<div>
    <div class="flex items-center justify-between w-full p-8">
        <div class="flex items-center gap-1">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary mr-5">
                <i data-lucide="{{ $icon }}" class="h-5 w-5 text-white"></i>
            </div>

            <div class="flex flex-col">
                <h1 class="text-3xl font-bold text-foreground">{{ $title }}</h1>
                <p class="text-muted-foreground mt-1">{{ $description }}</p>
            </div>
        </div>

        @if($event)
            <button
                wire:click="$dispatch('{{ $event }}')"
                class="cursor-pointer flex items-center bg-primary text-primary-foreground h-10 px-4 rounded-lg">
                <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
                <span>Novo</span>
            </button>
        @endif
    </div>
</div>
