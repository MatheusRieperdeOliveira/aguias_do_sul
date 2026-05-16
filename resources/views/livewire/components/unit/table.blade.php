<?php

use App\Models\Unit;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $address = '';

    public function getUnitsProperty()
    {
        return $this->unitsQuery()->paginate(15);
    }

    public function updatedAddress(): void
    {
        $this->resetPage();
    }

    #[On('unit-created')]
    public function refresh(): void
    {
        $this->resetPage();
    }

    #[On('unit-delete')]
    public function delete($unitId): void
    {
        Unit::destroy($unitId);
        $this->resetPage();
    }

    private function unitsQuery()
    {
        return Unit::query()->when(
            $this->address,
            fn ($q) => $q->where('name', 'ilike', '%'.$this->address.'%'),
        )->orderBy('name');
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
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
            </svg>
        </div>
    </div>

    <table class="w-full table-fixed border-collapse">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="w-1/2 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                <th class="w-1/4 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="w-1/4 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($this->units as $unit)
                <tr class="hover:bg-gray-50" wire:key="{{ $unit->id }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 truncate" title="{{ $unit->name }}">
                        {{ $unit->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 w-20">
                            Ativo
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex justify-center">
                            <livewire:components.unit.actions :unit="$unit" wire:key="actions-{{ $unit->id }}" />
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center text-sm text-gray-500">
                        Nenhuma unidade encontrada.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-4 py-3 border-t border-gray-200 bg-gray-50/50">
        {{ $this->units->links() }}
    </div>
</div>
