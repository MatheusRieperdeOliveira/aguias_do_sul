<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Unit;

new class extends Component {
    public bool $open = false;
    public ?int $unitId = null;

    public string $name = '';

    #[On('open-unit-modal')]
    public function openModal($unitId = null)
    {
        if ($unitId) {
            $this->unitId = $unitId;
            $unit = Unit::find($unitId);
            $this->name = $unit->name;
        }
        $this->open = true;
    }

    public function save()
    {
        if ($this->name === '') {
            $this->open = false;
        }

        $data = [
            'name' => $this->name,
            'status' => 'active',
        ];

        $unit = Unit::updateOrCreate(['id' => $this->unitId], $data);

        if ($unit) {
            $this->unitId = null;
            $this->name = '';
            $this->open = false;
            $this->dispatch('unit-created');
        }
    }

    public function closeModal()
    {
        $this->unitId = null;
        $this->name = '';
        $this->open = false;
    }

} ?>

<div>
    @if($open)
        <div
            wire:click.self="closeModal"
             wire:transition
             wire:transition.duration.200ms
             class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

            <div wire:transition
                 wire:transition.duration.200ms
                 class="bg-white rounded-lg p-6 w-[600px]">

                <h1 class="text-3xl font-bold mb-4">
                    {{ $unitId ? 'Editar Unidades' : 'Nova Unidade' }}
                </h1>

                <form wire:submit="save">
                    <div class="grid grid-cols-1 gap-3">
                        <label>
                            Nome
                            <input type="text" wire:model="name" placeholder="Nome"
                                   class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded">
                        </label>
                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                      clip-rule="evenodd"/>
                            </svg>
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
