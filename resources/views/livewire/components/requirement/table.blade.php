<?php

use App\Models\Pathfinder;
use App\Services\PathfinderService;
use Livewire\Attributes\{Computed, On};
use Livewire\Volt\Component;
use Carbon\Carbon;
use App\Services\RequirementService;
use App\Models\Requirement;

new class extends Component {
    public string $address = '';

    #[Computed]
    public function requirements()
    {
        return Requirement::query()->when($this->address, fn($q) => $q->where('title', 'ilike', "%{$this->address}%"))->get();
    }

    #[On('requirement-created')]
    public function refresh()
    {
        //
    }

    #[On('delete-requirement')]
    public function delete($requirementId)
    {
        Requirement::destroy($requirementId);
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

    <table class="w-full table-fixed border-collapse">
        <thead class="bg-gray-50 border-b border-gray-200">
        <tr>
            <th class="w-1/2 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
            <th class="w-1/6 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pontos</th>
            <th class="w-1/6 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
            <th class="w-1/6 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
        </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
        @foreach($this->requirements as $requirement)
            <tr class="hover:bg-gray-50" wire:key="{{ $requirement->id }}">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 truncate" title="{{$requirement->title}}">
                    {{$requirement->title}}
                </td>
                <td class="px-6 py-4 whitespace-nowrap flex justify-center">
                    <div class="flex items-center justify-center gap-1.5 bg-gradient-to-r from-amber-400 to-amber-500 text-white px-3 py-1 rounded-full shadow-sm min-w-[80px]">
                        <i data-lucide="crown" class="w-3 h-3"></i>
                        <span class="text-sm font-bold">
                            {{$requirement->score}}
                        </span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    @if($requirement->type === "pathfinder")
                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 w-24">
                            Desbravador
                        </span>
                    @else
                        <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 w-24">
                            Unidade
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                    <div class="flex justify-center">
                        <livewire:components.requirement.actions :requirement="$requirement" wire:key="actions-{{ $requirement->id }}"/>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
