<?php

use App\Models\Unit;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public bool $open = false;

    public ?int $unitId = null;

    public string $name = '';

    #[On('open-unit-modal')]
    public function openModal($unitId = null): void
    {
        $this->reset(['name', 'unitId']);
        $this->open = true;

        if ($unitId) {
            $this->unitId = (int) $unitId;
            $unit = Unit::find($this->unitId);

            if ($unit) {
                $this->name = $unit->name;
            }
        }
    }

    public function save(): void
    {
        $validated = $this->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255',
                    Rule::unique('units', 'name')->ignore($this->unitId),
                ],
            ],
            [
                'name.required' => 'Informe o nome da unidade.',
                'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
                'name.unique' => 'Já existe uma unidade com este nome.',
            ],
        );

        Unit::updateOrCreate(
            ['id' => $this->unitId],
            [
                'name' => $validated['name'],
                'status' => 'active',
            ],
        );

        $this->reset(['name', 'unitId']);
        $this->open = false;
        $this->dispatch('unit-created');
    }

    public function closeModal(): void
    {
        $this->reset(['name', 'unitId']);
        $this->open = false;
    }
};
?>

<div>
    @if ($open)
        <div wire:click.self="closeModal"
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
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                            <input type="text" wire:model="name" placeholder="Nome"
                                class="w-full h-11 rounded-lg border border-gray-300 p-2 @error('name') border-red-500 @enderror">
                            @error('name')
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
