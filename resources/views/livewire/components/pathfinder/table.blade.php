<?php

use App\Models\Pathfinder;
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
        return Pathfinder::query()
            ->with(['unit', 'points.requirement'])
            ->when($this->search, fn($q) => $q->where('name', 'ilike', "%{$this->search}%"))
            ->get();
    }

    #[On('pathfinder-created')]
    public function refresh()
    {
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

<div class="w-full space-y-4">
    <div class="flex justify-end">
        <div class="relative w-full sm:w-72">
            <input type="text" wire:model.live="search" placeholder="Pesquisar"
                class="w-full h-10 rounded-lg border border-gray-300 pl-10 pr-3">
            <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"
                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
            </svg>
        </div>
    </div>

    <div class="md:hidden space-y-3">
        @forelse ($this->pathfinders as $pathfinder)
            <div class="rounded-lg border border-gray-200 bg-white p-4 space-y-3" wire:key="mobile-{{ $pathfinder->id }}">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="font-semibold text-gray-900 truncate">{{ $pathfinder->name }}</p>
                        <p class="text-sm text-gray-500 truncate">{{ $pathfinder->email }}</p>
                    </div>
                    @if ($pathfinder->status === 'active')
                        <span class="shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Ativo</span>
                    @else
                        <span class="shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inativo</span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Telefone</p>
                        <p class="text-gray-700">{{ $pathfinder->full_phone ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Unidade</p>
                        <p class="text-gray-700 truncate">{{ $pathfinder->unit->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Aniversário</p>
                        <p class="text-gray-700">{{ Carbon::parse($pathfinder->birthday)->format('d/m/Y') }} · {{ $pathfinder->age }} anos</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider">Pontuação</p>
                        <button type="button" wire:click="openModalHistory({{ $pathfinder->id }})"
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $pathfinder->points->sum(fn($point) => $point->requirement->score) }}
                        </button>
                    </div>
                </div>

                <livewire:components.pathfinder.actions :pathfinder="$pathfinder" wire:key="actions-mobile-{{ $pathfinder->id }}" />
            </div>
        @empty
            <div class="rounded-lg border border-gray-200 bg-white p-6 text-center text-sm text-gray-500">
                Nenhum desbravador encontrado
            </div>
        @endforelse
    </div>

    <div class="hidden md:block overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full table-fixed border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="w-[18%] px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                    <th class="w-[10%] px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="w-[12%] px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Telefone</th>
                    <th class="w-[18%] px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="w-[12%] px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aniversário</th>
                    <th class="w-[12%] px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Unidade</th>
                    <th class="w-[8%] px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pontuação</th>
                    <th class="w-[10%] px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($this->pathfinders as $pathfinder)
                    <tr class="hover:bg-gray-50" wire:key="desktop-{{ $pathfinder->id }}">
                        <td class="px-4 py-4 text-sm font-medium text-gray-900 truncate" title="{{ $pathfinder->name }}">
                            {{ $pathfinder->name }}
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if ($pathfinder->status === 'active')
                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 w-20">Ativo</span>
                            @else
                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 w-20">Inativo</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center text-sm text-gray-500 truncate">{{ $pathfinder->full_phone }}</td>
                        <td class="px-4 py-4 text-center text-sm text-gray-500 truncate" title="{{ $pathfinder->email }}">{{ $pathfinder->email }}</td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="flex items-center gap-1 text-sm font-medium text-gray-900">
                                    <i data-lucide="cake" class="w-3 h-3 text-gray-400"></i>
                                    <span>{{ Carbon::parse($pathfinder->birthday)->format('d/m/Y') }}</span>
                                </div>
                                <span class="text-xs text-gray-500">{{ $pathfinder->age }} anos</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center text-sm text-gray-500 truncate">{{ $pathfinder->unit->name ?? '-' }}</td>
                        <td class="px-4 py-4 text-center text-sm font-medium cursor-pointer" wire:click="openModalHistory({{ $pathfinder->id }})">
                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 w-20">
                                {{ $pathfinder->points->sum(fn($point) => $point->requirement->score) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center text-sm font-medium">
                            <livewire:components.pathfinder.actions :pathfinder="$pathfinder" wire:key="actions-desktop-{{ $pathfinder->id }}" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($showModal)
        <div
            wire:transition.opacity
            class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4"
        >
            <div class="bg-white rounded-lg p-4 md:p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium">Histórico de pontos</h2>
                    <button wire:click="closeModalHistory" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <livewire:components.pathfinder.history :pathfinderId="$pathfinderId"/>
            </div>
        </div>
    @endif
</div>
