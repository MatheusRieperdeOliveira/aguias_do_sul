<?php

use App\Models\Pathfinder;
use App\Services\PathfinderService;
use Livewire\Attributes\{Computed, On};
use Livewire\Volt\Component;
use Carbon\Carbon;
use App\Services\RequirementService;
use App\Models\Requirement;

new class extends Component {

    #[Computed]
    public function requirements()
    {
        return Requirement::all();
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

    <table class="min-w-full table-fixed border-collapse">
        <thead class="bg-white">
        <tr>
            <th class="min-w-[180px] px-4 py-2 text-left font-medium">Título</th>
            <th class="min-w-[120px] px-4 py-2 text-left font-medium">Pontos</th>
            <th class="min-w-[120px] px-4 py-2 text-left font-medium">Tipo</th>
            <th class="min-w-[50px] px-4 py-2 text-left font-medium">Ações</th>
        </tr>
        </thead>

        <tbody>
        @foreach($this->requirements as $requirement)
            <tr class="border-t border-gray-200">
                <td class="px-4 py-2">{{$requirement->title}}</td>
                <td class="px-4 py-2">
                    <div class="w-20 flex items-center gap-1.5 bg-gradient-to-r from-amber-400 to-amber-500 text-white px-3 py-1.5 rounded-full shadow-md">
                        <i data-lucide="crown" class="w-3 h-3"></i>
                        <p>
                            {{$requirement->score}}
                        </p>
                    </div>
                </td>
                <td class="px-4 py-2">
                    @if($requirement->type === "pathfinder")
                        <div class="bg-primary w-30 h-6 text-white text-center rounded-lg">
                            desbravador
                        </div>
                    @else
                        <div class="bg-orange-600 w-30 h-6 text-white text-center rounded-lg">
                            unidade
                        </div>
                    @endif
                </td>
                <td class="px-4 py-2"><livewire:components.requirement.actions :requirement="$requirement"/></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
