<?php

use App\Models\Requirement;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public bool $open = false;

    public ?int $requirementId = null;

    public ?array $requirementsOptions = null;

    public string $title = '';

    public int $score = 0;

    public string $type = '';

    public function mount(): void
    {
        $this->requirementsOptions = ['unit' => 'Unidade', 'pathfinder' => 'Desbravador'];
    }

    #[On('open-requirement-modal')]
    public function openModal($requirementId = null): void
    {
        $this->reset(['title', 'score', 'type', 'requirementId']);
        $this->open = true;
        $this->type = 'pathfinder';

        if ($requirementId) {
            $this->requirementId = (int) $requirementId;
            $requirement = Requirement::find($this->requirementId);

            if ($requirement) {
                $this->title = $requirement->title;
                $this->score = (int) $requirement->score;
                $this->type = $requirement->type;
            }
        }
    }

    public function save(): void
    {
        $validated = $this->validate(
            [
                'title' => ['required', 'string', 'min:2', 'max:255'],
                'score' => ['required', 'integer', 'min:0', 'max:999999'],
                'type' => ['required', 'in:unit,pathfinder'],
            ],
            [
                'title.required' => 'Informe o título.',
                'title.min' => 'O título deve ter pelo menos 2 caracteres.',
                'score.required' => 'Informe a pontuação.',
                'score.min' => 'A pontuação não pode ser negativa.',
                'type.required' => 'Selecione o tipo.',
                'type.in' => 'Tipo inválido.',
            ],
        );

        Requirement::updateOrCreate(
            ['id' => $this->requirementId],
            [
                'title' => $validated['title'],
                'score' => $validated['score'],
                'type' => $validated['type'],
            ],
        );

        $this->reset(['title', 'score', 'type', 'requirementId']);
        $this->type = 'pathfinder';
        $this->open = false;
        $this->dispatch('requirement-created');
    }

    public function closeModal(): void
    {
        $this->reset(['title', 'score', 'type', 'requirementId']);
        $this->type = 'pathfinder';
        $this->open = false;
    }

    #[On('delete-requirement')]
    public function deleteRequirement($requirementId): void
    {
        Requirement::destroy($requirementId);
        $this->dispatch('requirement-deleted');
    }
};
?>

<div>
    @if ($open)
        <div wire:transition
            wire:click.self="closeModal"
            wire:transition.duration.200ms
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

            <div wire:transition
                wire:transition.duration.200ms
                class="bg-white rounded-lg p-6 w-[600px]">

                <h1 class="text-3xl font-bold mb-4">
                    {{ $requirementId ? 'Editar requisitos' : 'Novo requisito' }}
                </h1>

                <form wire:submit="save">
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                            <input type="text" wire:model="title" placeholder="Título"
                                class="w-full h-11 rounded-lg border border-gray-300 p-2 @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pontos</label>
                            <input type="number" wire:model="score" placeholder="Pontos" min="0"
                                class="w-full h-11 rounded-lg border border-gray-300 p-2 @error('score') border-red-500 @enderror">
                            @error('score')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                            <div class="relative w-full">
                                <select wire:model="type"
                                    class="w-full h-11 appearance-none rounded-lg border border-gray-300 bg-white px-4 pr-10 text-sm font-medium text-gray-700 shadow-sm transition focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary hover:border-gray-400 cursor-pointer @error('type') border-red-500 @enderror">
                                    @foreach ($requirementsOptions as $index => $option)
                                        <option value="{{ $index }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            @error('type')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" wire:click="closeModal" class="cursor-pointer px-4 py-2 bg-gray-300 rounded">
                            Cancelar
                        </button>

                        <button type="submit" class="cursor-pointer px-4 py-2 bg-primary text-white rounded">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
