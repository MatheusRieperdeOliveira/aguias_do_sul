<?php

use Livewire\Attributes\Reactive;
use Livewire\Component;
use App\Models\Requirement;

new class extends Component {
    public Requirement $requirement;
};
?>

<div class="w-full h-8 grid grid-cols-3 rounded-xl overflow-hidden text-white overflow-visible">
    <div class="group relative flex items-center justify-center bg-red-600 rounded-l-lg">
        <button
            wire:click="$dispatch('delete-requirement', { requirementId:{{$requirement->id}}})"
            class="cursor-pointer flex items-center justify-center w-full h-full">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
        </button>
        <div class="absolute bottom-full mb-2 hidden group-hover:block w-max px-2 py-1 bg-gray-800 text-white text-xs rounded shadow-lg z-50">
            deletar
        </div>
    </div>
    <div class="group relative flex items-center justify-center bg-blue-600 rounded-r-lg">
        <button
            wire:click="$dispatch('open-requirement-modal', { requirementId:{{$requirement->id}}})"
            class="cursor-pointer flex items-center justify-center w-full h-full">
            <i data-lucide="pencil" class="w-4 h-4"></i>
        </button>
        <div class="absolute bottom-full mb-2 hidden group-hover:block w-max px-2 py-1 bg-gray-800 text-white text-xs rounded shadow-lg z-50">
            editar
        </div>
    </div>
</div>
