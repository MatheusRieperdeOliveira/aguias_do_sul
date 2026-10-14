<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $title;
    public string $icon;
    public string $description;
    public string $event;
};
?>

<div
    class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between w-full p-4 md:p-8"
>
    <div class="flex items-center gap-3 min-w-0">
        <div
            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary"
        >
            <i data-lucide="{{ $icon }}" class="h-5 w-5 text-white"></i>
        </div>

        <div class="flex flex-col min-w-0">
            <h1 class="text-2xl md:text-3xl font-bold text-foreground truncate">
                {{ $title }}
            </h1>
            <p class="text-sm text-muted-foreground mt-0.5 truncate">{{ $description }}</p>
        </div>
    </div>

    @if ($event)
        <button
            wire:click="$dispatch('{{ $event }}')"
            class="cursor-pointer flex items-center justify-center shrink-0 bg-primary text-primary-foreground h-10 px-4 rounded-lg w-full sm:w-auto"
        >
            <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
            <span>Novo</span>
        </button>
    @endif
</div>
