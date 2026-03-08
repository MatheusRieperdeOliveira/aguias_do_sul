<?php

use Livewire\Attributes\Reactive;
use Livewire\Component;
use App\Models\Unit;

new class extends Component {
    public Unit $unit;
};

?>

<div class="w-80 h-8.5 grid grid-cols-3 rounded-xl overflow-hidden text-white overflow-visible">
    <div class="group relative flex items-center justify-center bg-red-600 rounded-l-lg">
        <button
            wire:click="$dispatch('unit-delete', { unitId:{{$unit->id}}})"
            class="cursor-pointer flex items-center justify-center w-full h-5">
            <i data-lucide="trash-2" class="w-5 h-5"></i>
        </button>
        <div class="absolute bottom-full mb-2 hidden group-hover:block w-max px-2 py-1 bg-gray-800 text-white text-xs rounded shadow-lg z-50">
            deletar
        </div>
    </div>

    <div class="group relative flex items-center justify-center bg-blue-600 rounded-r-lg">
        <button
            wire:click="$dispatch('open-unit-modal', { unitId:{{$unit->id}}})"
            class="cursor-pointer flex items-center justify-center w-5 h-5">
            <i data-lucide="pencil" class="w-4.5 h-4.5"></i>
        </button>
        <div class="absolute bottom-full mb-2 hidden group-hover:block w-max px-2 py-1 bg-gray-800 text-white text-xs rounded shadow-lg z-50">
            editar
        </div>
    </div>
</div>
