<?php

use App\Models\Pathfinder;
use App\Models\Unit;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Requirement;

new class extends Component {
    public bool $open = false;
    public ?int $requirementId = null;

    public string $title = "";
    public int $score = 0;

    #[On('open-requirement-modal')]
    public function openModal($requirementId = null){

        if($requirementId){
            $this->requirementId = $requirementId;
            $requirement = Requirement::find($requirementId);
            $this->title = $requirement->title;
            $this->score = $requirement->score;
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
        ];

        $requirement = Requirement::updateOrCreate(['id' => $this->requirementId], $data);

        if ($requirement){
            $this->title = '';
            $this->score = 0;
            $this->open = false;
            $this->dispatch('requirement-created');
        }
    }

    public function closeModal()
    {
        $this->title = '';
        $this->score = 0;
        $this->open = false;
    }
}?>

<div>
    @if($open)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

            <div class="bg-white rounded-lg p-6 w-[600px]">

                <h1 class="text-3xl font-bold mb-4">
                    {{ $requirementId ? 'Editar requisitos' : 'Novo requisito' }}
                </h1>

                <form wire:submit="save">
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" wire:model="title" placeholder="Título" class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded">
                        <input type="number" wire:model="score" placeholder="Pontos" class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded">

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
