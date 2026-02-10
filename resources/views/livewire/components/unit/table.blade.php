<?php

use App\Models\Unit;
use App\Services\UnitService;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public $units = [];

    public function mount(UnitService $service)
    {
        $this->load($service);
    }

    #[On(['unit-created', 'unit-inactive', 'unit-active'])]
    public function load(UnitService $service)
    {
        $this->units = $service->getUnits();
    }

    public function inactivate(string $unit_id)
    {
        Unit::query()->where('id', $unit_id)->update([
            'status' => 'inactive'
        ]);

        $this->dispatch('unit-inactive');
    }

    public function activate(string $unit_id)
    {
        Unit::query()->where('id', $unit_id)->update([
            'status' => 'active'
        ]);

        $this->dispatch('unit-active');
    }
};
?>

<div class="w-full overflow-x-auto rounded-lg border border-gray-200">
    <div class="w-full h-13 flex items-center justify-end px-4">
        <div class="relative">
            <input type="text" wire:model="address" placeholder="Pesquisar"
                   class="h-10 rounded-lg border border-gray-300 pl-10 pr-2">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"
                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z"/>
            </svg>
        </div>
    </div>

    <table class="min-w-full table-fixed border-collapse">
        <thead class="bg-white">
        <tr>
            <th class="min-w-[180px] px-4 py-2 text-left font-medium">Nome</th>
            <th class="min-w-[120px] px-4 py-2 text-left font-medium">Status</th>
            <th class="min-w-[50px] px-4 py-2 text-left font-medium">Ações</th>
        </tr>
        </thead>

        <tbody>
        @foreach($units as $unit)
            <tr class="border-t border-gray-200">
                <td class="px-4 py-2">{{$unit->name}}</td>
                <td class="px-4 py-2">
                    @if($unit->status === 'active')
                        <div
                            class="w-13 h-6 bg-primary rounded-md flex items-center justify-center text-white text-sm font-semibold">
                            <p>
                                Ativo
                            </p>
                        </div>
                    @else
                        <div
                            class="w-13 h-6 bg-red-500 rounded-md flex items-center justify-center text-white text-sm font-semibold">
                            <p>
                                Inativo
                            </p>
                        </div>
                    @endif
                </td>
                <td class="px-4 py-2">
                    <div class="w-full h-8 grid grid-cols-2 rounded-xl overflow-hidden text-white overflow-visible">
                        @if($unit->status === 'active')
                            <div class="group relative flex items-center justify-center bg-red-600 rounded-l-lg">
                                <button
                                    wire:click="inactivate({{$unit->id}})"
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
                                    wire:click="activate({{$unit->id}})"
                                    class="cursor-pointer flex items-center justify-center w-full h-full">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </button>
                                <div class="absolute bottom-full mb-2 hidden group-hover:block w-max px-2 py-1 bg-gray-800 text-white text-xs rounded shadow-lg z-50">
                                    Ativar
                                </div>
                            </div>
                        @endif
                        <div class="group relative flex items-center justify-center bg-blue-600 rounded-r-lg">
                            <button
                                wire:click="$dispatch('open-unit-modal', { unitId: {{ $unit->id }} })"
                                class="cursor-pointer flex items-center justify-center w-full h-full">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </button>
                            <div class="absolute bottom-full mb-2 hidden group-hover:block w-max px-2 py-1 bg-gray-800 text-white text-xs rounded shadow-lg z-50">
                                Editar
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
