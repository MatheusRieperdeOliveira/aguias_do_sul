<?php

use App\Models\Pathfinder;
use App\Models\Unit;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Carbon\Carbon;

new class extends Component {
    public ?int $pathfinderId = null;
    public string $name = '';
    public ?int $unit_id = null;
    public string $email = '';
    public string $full_phone = '';
    public string $birthday = '';
    public string $address = '';

    public bool $open = false;

    #[On('open-pathfinder-modal')]
    public function openModal(int|string|null $pathfinderId = null): void
    {
        $this->reset();
        $this->open = true;

        if ($pathfinderId === null || $pathfinderId === '') {
            return;
        }

        $pathfinder = Pathfinder::find((int) $pathfinderId);

        if (!$pathfinder) {
            return;
        }

        $this->pathfinderId = $pathfinder->id;
        $this->name = (string) $pathfinder->name;
        $this->unit_id = $pathfinder->unit_id;
        $this->email = (string) ($pathfinder->email ?? '');
        $this->full_phone = (string) ($pathfinder->full_phone ?? '');
        $this->birthday = $pathfinder->birthday
            ? Carbon::parse($pathfinder->birthday)->format('Y-m-d')
            : '';
        $this->address = (string) ($pathfinder->address ?? '');
    }

    public function closeModal(): void
    {
        $this->reset();
    }

    public function save(): void
    {
        $validated = $this->validate(
            [
                'name' => ['required', 'string', 'min:2', 'max:255'],
                'unit_id' => ['required', 'exists:units,id'],
                'email' => ['nullable', 'email', 'max:255'],
                'full_phone' => ['nullable', 'string', 'max:40'],
                'birthday' => ['required', 'date', 'before_or_equal:today', 'after:1900-01-01'],
                'address' => ['nullable', 'string', 'max:500'],
            ],
            [
                'name.required' => 'Informe o nome.',
                'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
                'unit_id.required' => 'Selecione a unidade.',
                'unit_id.exists' => 'Unidade inválida.',
                'email.email' => 'Informe um e-mail válido.',
                'birthday.required' => 'Informe a data de nascimento.',
                'birthday.before_or_equal' => 'A data de nascimento não pode ser futura.',
                'birthday.after' => 'Data de nascimento inválida.',
            ],
        );

        $data = [
            'name' => $validated['name'],
            'email' => ($validated['email'] ?? '') === '' ? null : $validated['email'],
            'age' => Carbon::parse($validated['birthday'])->age,
            'full_phone' => ($validated['full_phone'] ?? '') === '' ? null : $validated['full_phone'],
            'birthday' => $validated['birthday'],
            'address' => ($validated['address'] ?? '') === '' ? null : $validated['address'],
            'unit_id' => $validated['unit_id'],
        ];

        if (! $this->pathfinderId) {
            $data['status'] = 'active';
        }

        Pathfinder::updateOrCreate(
            ['id' => $this->pathfinderId],
            $data,
        );

        $this->reset();
        $this->dispatch('pathfinder-created');
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
    @if ($open)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div
                wire:transition
                wire:transition.duration.200ms
                wire:click.self="closeModal"
                class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
            >
                <div
                    wire:key="pathfinder-form-{{ $pathfinderId ?? 'new' }}"
                    class="bg-white rounded-lg p-6 w-[600px]"
                    @click.stop
                >

                    <h1 class="text-3xl font-bold mb-4">
                        {{ $pathfinderId ? 'Editar desbravador' : 'Novo desbravador' }}
                    </h1>

                    <form wire:submit="save">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <input type="text" wire:model="name" placeholder="Nome"
                                    class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="email" wire:model="email" placeholder="Email (opcional)"
                                    class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="text" wire:model="full_phone" placeholder="Telefone (opcional)"
                                    class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded @error('full_phone') border-red-500 @enderror">
                                @error('full_phone')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="date" wire:model="birthday"
                                    class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded @error('birthday') border-red-500 @enderror">
                                @error('birthday')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="text" wire:model="address" placeholder="Endereço (opcional)"
                                    class="w-full h-11 rounded-lg border border-gray-300 p-2 rounded @error('address') border-red-500 @enderror">
                                @error('address')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
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
            @error('unit_id') border-red-500 @enderror
        "
                                >
                                    <option value="" class="text-gray-400">Selecione a unidade</option>

                                    @foreach($unitOptions as $index => $option)
                                        <option value="{{ $index }}">
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>

                                <div
                                    class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                         fill="currentColor">
                                        <path fill-rule="evenodd"
                                              d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                @error('unit_id')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" wire:click="closeModal"
                                    class="cursor-pointer px-4 py-2 bg-gray-300 rounded">
                                Cancelar
                            </button>

                            <button type="submit" class="cursor-pointer px-4 py-2 bg-primary text-white rounded">
                                Salvar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
