<?php

use App\Models\Unit;
use Livewire\Attributes\{Computed, On};
use Livewire\Volt\Component;

new class extends Component {
    public string $address = '';

    #[Computed]
    public function units()
    {
        return Unit::query()
            ->when($this->address, fn($q) => $q->where('name', 'ilike', "%{$this->address}%"))
            ->get();
    }

    #[On('unit-created')]
    public function refresh()
    {
        //
    }

    #[On('unit-delete')]
    public function delete($unitId)
    {
        Unit::destroy($unitId);
    }

};
?>

<div class="w-full overflow-x-auto rounded-lg border border-gray-200">
    <div class="w-full h-13 flex items-center justify-end px-4">
        <div class="relative">
            <input type="text" wire:model.live="address" placeholder="Pesquisar"
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
            <th class="min-w-[120px] px-4 py-2 text-center font-medium">Ações</th>
        </tr>
        </thead>

        <tbody>
        @foreach($this->units as $unit)
            <tr class="border-t border-gray-200" wire:key="{{ $unit->id }}">
                <td class="px-4 py-2">{{$unit->name}}</td>
                <td class="px-4 py-2">
                        <div
                            class="w-13 h-6 bg-primary rounded-md flex items-center justify-center text-white text-sm font-semibold">
                            <p>
                                Ativo
                            </p>
                        </div>

                </td>
                <td class="px-4 py-2"><livewire:components.unit.actions :unit="$unit"/>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
