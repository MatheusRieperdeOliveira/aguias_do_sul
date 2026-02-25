<?php

use App\Models\Unit;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public ?int $unitId = null;
    public $name = '';

    public bool $open = false;

    #[On('open-unit-modal')]
    public function openModal($unitId = null)
    {
        $this->reset();
        $this->open = true;

        if ($unitId) {
            $this->unitId = $unitId;
            $unit = Unit::find($unitId);

            if ($unit) {
                $this->name = $unit->name;
            }
        }
    }

    public function closeModal()
    {
        $this->reset();
        $this->open = false;
    }

    public function save()
    {
        $data = [
            'name' => $this->name,
        ];

        if (!$this->unitId) {
            $data['status'] = 'active';
        }

        Unit::updateOrCreate(
            ['id' => $this->unitId],
            $data
        );

        $this->dispatch('unit-created');
        $this->closeModal();
    }
};
?>

<div>
    @if($open)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

            <div class="bg-white rounded-lg p-6 w-[600px]">

                <h1 class="text-3xl font-bold mb-4">
                    {{ $unitId ? 'Editar unidade' : 'Nova unidade' }}
                </h1>

                <form wire:submit="save">
                    <div class="grid grid-cols-1 gap-3">
                        <input type="text" wire:model="name" placeholder="Nome" class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded">
                    </div>

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
