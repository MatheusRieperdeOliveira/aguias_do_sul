<?php
use function Livewire\Volt\{state};
use App\Models\Pathfinder;

state(['pathfinder' => fn (Pathfinder $pathfinder) => $pathfinder]);

?>

<div class="w-40 h-10 grid grid-cols-3 rounded-xl overflow-hidden text-white overflow-visible">
    @if($pathfinder->status === 'active')
        <div class="group relative flex items-center justify-center bg-red-600 rounded-l-lg">
            <button
                wire:click="$dispatch('inactivate-pathfinder', { pathfinderId: '{{ $pathfinder->id }}' })"
                class="cursor-pointer flex items-center justify-center w-full h-full">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            <div class="absolute bottom-full mb-2 hidden group-hover:block w-max px-2 py-1 bg-gray-800 text-white text-xs rounded shadow-lg z-50">
                Inativar
            </div>
        </div>
    @else
        <div class="group relative flex items-center justify-center bg-green-600 rounded-l-lg">
            <button
                wire:click="$dispatch('activate-pathfinder', { pathfinderId: '{{ $pathfinder->id }}' })"
                class="cursor-pointer flex items-center justify-center w-full h-full">
                <i data-lucide="check" class="w-4 h-4"></i>
            </button>
            <div class="absolute bottom-full mb-2 hidden group-hover:block w-max px-2 py-1 bg-gray-800 text-white text-xs rounded shadow-lg z-50">
                Ativar
            </div>
        </div>
    @endif

    <div class="group relative flex items-center justify-center bg-blue-600">
        <button
            wire:click="$dispatch('open-pathfinder-modal', { pathfinderId: {{ $pathfinder->id }} })"
            class="cursor-pointer flex items-center justify-center w-full h-full">
            <i data-lucide="pencil" class="w-4 h-4"></i>
        </button>
        <div class="absolute bottom-full mb-2 hidden group-hover:block w-max px-2 py-1 bg-gray-800 text-white text-xs rounded shadow-lg z-50">
            Editar
        </div>
    </div>

    <div class="group relative flex items-center justify-center bg-violet-600 rounded-r-lg">
        <button
            wire:click="$dispatch('open-barcode-modal', { pathfinderId: {{ $pathfinder->id }} })"
            class="cursor-pointer flex items-center justify-center w-full h-full">
            <i data-lucide="qr-code" class="w-4 h-4"></i>
        </button>
        <div class="absolute bottom-full mb-2 hidden group-hover:block w-max px-2 py-1 bg-gray-800 text-white text-xs rounded shadow-lg z-50">
            Barcode
        </div>
    </div>
</div>
