<?php
use function Livewire\Volt\{state};

state(['unitId' => null]);

$edit = function ($unitId) {
    $this->unitId = $unitId;
};

?>

<x-layouts.app>
    <div class="w-full h-full flex flex-col gap-4">
        <div class="w-full h-12 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Unidades</h1>
            <button
                class="w-40 h-10 bg-primary rounded-md text-white"
                wire:click="$dispatch('open-unit-modal')">
                Adicionar Unidade
            </button>
        </div>
        <livewire:components.unit.table/>
    </div>

    <x-modal name="unit-modal" title="Adicionar Unidade">
        <livewire:components.unit.form :unitId="$unitId"/>
    </x-modal>
</x-layouts.app>
