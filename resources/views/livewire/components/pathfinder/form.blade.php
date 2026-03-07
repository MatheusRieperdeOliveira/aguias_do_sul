<?php

use App\Models\Pathfinder;
use App\Models\Unit;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Carbon\Carbon;

new class extends Component {
    public ?int $pathfinderId = null;
    public $name = '';
    public ?int $unit_id = null;
    public $email = '';
    public $full_phone = '';
    public $birthday = '';
    public $address = '';

    public bool $open = false;

    #[On('open-pathfinder-modal')]
    public function openModal($pathfinderId = null)
    {
        $this->reset();
        $this->open = true;

        if ($pathfinderId) {
            $this->pathfinderId = $pathfinderId;
            $pathfinder = Pathfinder::find($pathfinderId);

            if ($pathfinder) {
                $this->name = $pathfinder->name;
                $this->unit_id = $pathfinder->unit_id;
                $this->email = $pathfinder->email;
                $this->full_phone = $pathfinder->full_phone;
                $this->birthday = $pathfinder->birthday;
                $this->address = $pathfinder->address;
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
            'email' => $this->email,
            'age' => Carbon::parse($this->birthday)->age,
            'full_phone' => $this->full_phone,
            'birthday' => $this->birthday,
            'address' => $this->address,
            'unit_id' => $this->unit_id,
        ];

        if (!$this->pathfinderId) {
            $data['status'] = 'active';
            $data['barcode'] = Str::random(40);
        }

        Pathfinder::updateOrCreate(
            ['id' => $this->pathfinderId],
            $data
        );

        $this->closeModal();
    }

    public function with(): array
    {
        return [
            'unitOptions' => Unit::all()->pluck('name', 'id'),
        ];
    }
};
?>

<div>
    @if($open)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

            <div class="bg-white rounded-lg p-6 w-[600px]">

                <h1 class="text-3xl font-bold mb-4">
                    {{ $pathfinderId ? 'Editar desbravador' : 'Novo desbravador' }}
                </h1>

                <form wire:submit="save">
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" wire:model="name" placeholder="Nome" class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded">
                        <input type="email" wire:model="email" placeholder="Email" class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded">
                        <input type="text" wire:model="full_phone" placeholder="Telefone" class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded">
                        <input type="date" wire:model="birthday" class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded">
                        <input type="text" wire:model="address" placeholder="Endereço" class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded">
                        <div class="relative w-full mb-3">
                            <select
                                wire:model="unit_id"
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
            font-[GeistMono]
        "
                            >
                                <option value="" class="text-gray-400">Selecione a unidade</option>

                                @foreach($unitOptions as $index => $option)
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
