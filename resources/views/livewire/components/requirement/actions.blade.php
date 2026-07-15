<?php

use Livewire\Component;
use App\Models\Requirement;

new class extends Component {
    public Requirement $requirement;
};
?>

<div class="w-full h-8 grid grid-cols-2 rounded-xl text-white overflow-hidden">
    <div class="group relative flex items-center justify-center bg-red-600">
        <button
            wire:click="$dispatch('delete-requirement', { requirementId:{{$requirement->id}}})"
            class="cursor-pointer flex items-center justify-center w-full h-full">
            <i data-lucide="trash-2" class="w-4 h-4"></i>
        </button>
        <div class="absolute bottom-full mb-2 hidden group-hover:block w-max px-2 py-1 bg-gray-800 text-white text-xs rounded shadow-lg z-50">
            Deletar
        </div>
    </div>
    <div class="group relative flex items-center justify-center bg-blue-600">
        <button
            wire:click="$dispatch('open-requirement-modal', { requirementId:{{$requirement->id}}})"
            class="cursor-pointer flex items-center justify-center w-full h-full">
            <i data-lucide="pencil" class="w-4 h-4"></i>
        </button>
        <div class="absolute bottom-full mb-2 hidden group-hover:block w-max px-2 py-1 bg-gray-800 text-white text-xs rounded shadow-lg z-50">
            Editar
        </div>
    </div>
</div>
