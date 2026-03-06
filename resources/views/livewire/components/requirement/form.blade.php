<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Requirement;

new class extends Component {
    public bool $open = false;
    public ?int $requirementId = null;
    public ?array $requirementsOptions = null;

    public string $title = "";
    public int $score = 0;
    public string $type = "";

    public function mount()
    {
        $this->requirementsOptions = ["unit" => "Unidade", "pathfinder" => "Desbravador"];
    }

    #[On('open-requirement-modal')]
    public function openModal($requirementId = null){
        if($requirementId){
            $this->requirementId = $requirementId;
            $requirement = Requirement::find($requirementId);
            $this->title = $requirement->title;
            $this->score = $requirement->score;
            $this->type = $requirement->type;
        }
        $this->open = true;

    }

    public function save(){
        if ($this->title === "" && $this->score === 0){
            $this->open = false;
            return;
        }

        $data = [
            'title' => $this->title,
            'score' => $this->score,
            'type' => $this->type,
        ];

        $requirement = Requirement::updateOrCreate(['id' => $this->requirementId], $data);

        if ($requirement){
            $this->title = '';
            $this->score = 0;
            $this->type = '';
            $this->open = false;
            $this->dispatch('requirement-created');
        }
    }

    public function closeModal()
    {
        $this->title = '';
        $this->score = 0;
        $this->type = '';
        $this->open = false;
    }

    #[On('delete-requirement')]
    public function deleteRequirement($requirementId)
    {
        Requirement::destroy($requirementId);
        $this->dispatch('requirement-deleted');
    }
}?>

<div>
    @if($open)
        <div wire:transition
             wire:transition.duration.200ms
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

            <div wire:transition
                 wire:transition.duration.200ms
                 class="bg-white rounded-lg p-6 w-[600px]">

                <h1 class="text-3xl font-bold mb-4">
                    {{ $requirementId ? 'Editar requisitos' : 'Novo requisito' }}
                </h1>

                <form wire:submit="save">
                    <div class="grid grid-cols-2 gap-3">
                        <label>
                            Título
                            <input type="text" wire:model="title" placeholder="Título" class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded">
                        </label>

                        <label>
                            Pontos
                            <input type="number" wire:model="score" placeholder="Pontos" class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded">
                        </label>

                        <label>
                            Tipo
                            <div class="grid grid-cols-2 gap-3">
                                <div class="relative w-full mb-3">
                                    <select
                                        wire:model="type"
                                        class="
            w-full h-11
            appearance-none
            rounded-lg
            border border-gray-300
            bg-white
            px-4 pr-10
            text-sm font-medium font-[Geist]
            text-gray-700
            shadow-sm
            transition
            focus:outline-none
            focus:ring-2
            focus:ring-primary
            focus:border-primary
            hover:border-gray-400
            cursor-pointer
            font-[GeistMono]"
                                    >
                                        @foreach($requirementsOptions as $index =>$option)
                                            <option value="{{ $index }}">
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                             fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </label>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-300 rounded">
                            Cancelar
                        </button>

                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
