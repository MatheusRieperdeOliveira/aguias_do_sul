<?php

use App\Models\Pathfinder;
use App\Services\PathfinderService;
use Livewire\Attributes\{Computed, On};
use Livewire\Volt\Component;
use Carbon\Carbon;

new class extends Component {
    public string $search = '';
    public bool $showModal = false;
    public ?int $pathfinderId = null;

    #[Computed]
    public function pathfinders()
    {
        return Pathfinder::query()->when($this->search, fn($q) => $q->where('name', 'ilike', "%{$this->search}%"))->get();
    }

    #[On('pathfinder-created')]
    public function refresh()
    {
        //
    }

    #[On('delete-pathfinder')]
    public function delete($pathfinderId)
    {
        Pathfinder::destroy($pathfinderId);
    }

    public function openModalHistory($pathfinderId)
    {
        $this->pathfinderId = $pathfinderId;
        $this->showModal = true;
    }

    
    public function closeModalHistory()
    {
        $this->pathfinderId = null;
        $this->showModal = false;
    }
};
?>

<div class="w-full overflow-x-auto rounded-lg border border-gray-200">
    <div class="w-full h-13 flex items-center justify-end px-4">
        <div class="relative">
            <input type="text" wire:model.live="search" placeholder="Pesquisar"
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
                <th class="w-1/6 px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                <th class="w-24 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="w-1/6 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone</th>
                <th class="w-1/6 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="w-1/6 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aniversário</th>
                <th class="w-1/6 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Unidade</th>
                <th class="w-1/6 px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pontuação</th>
                <th class="w-24 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($this->pathfinders as $pathfinder)
                <tr class="hover:bg-gray-50" wire:key="{{ $pathfinder->id }}">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 truncate"
                        title="{{ $pathfinder->name }}">
                        {{ $pathfinder->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @if ($pathfinder->status === 'active')
                            <span
                                class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 w-20">
                                Ativo
                            </span>
                        @else
                            <span
                                class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 w-20">
                                Inativo
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                        {{ $pathfinder->full_phone }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 truncate"
                        title="{{ $pathfinder->email }}">
                        {{ $pathfinder->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="flex items-center gap-1 text-sm font-medium text-gray-900">
                                <i data-lucide="cake" class="w-3 h-3 text-gray-400"></i>
                                <span>{{ Carbon::parse($pathfinder->birthday)->format('d/m/Y') }}</span>
                            </div>
                            <span class="text-xs text-gray-500">
                                {{ $pathfinder->age }} anos
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center text-sm text-gray-500 truncate">
                        {{ $pathfinder->unit->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium cursor-pointer" wire:click="openModalHistory({{ $pathfinder->id }})">
                        <div class="flex justify-center">
                            <div class="relative group">
                                <span
                                    class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 w-20">
                                    {{ $pathfinder->points->sum(fn($point) => $point->requirement->score) }}
                                </span>

                                <div
                                    class="absolute bottom-full mb-2 hidden group-hover:block bg-gray-900 text-white text-xs rounded px-2 py-1 whitespace-nowrap">
                                    Clique para ver o histórico de pontos
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="pr-1 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex justify-center">
                            <livewire:components.pathfinder.actions :pathfinder="$pathfinder"
                                wire:key="actions-{{ $pathfinder->id }}" />
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

   @if($showModal)
    <div 
        wire:transition.opacity
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center h-screen w-screen"
    >
        <div 
            wire:transition
            class="bg-white rounded-lg p-6 w-full h-full
                   transition-all duration-300
                   data-[enter]:translate-y-full
                   data-[enter-to]:translate-y-0
                   data-[leave]:translate-y-0
                   data-[leave-to]:-translate-y-full"
        >
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-medium mb-4">Histórico de pontos</h2>
                <button wire:click="closeModalHistory" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div>
                <livewire:components.pathfinder.history :pathfinderId="$pathfinderId"/>
            </div>
        </div>
    </div>
@endif
</div>
