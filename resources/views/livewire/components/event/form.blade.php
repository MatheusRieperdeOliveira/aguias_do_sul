<?php

use App\Models\Event;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public bool $open = false;

    public ?int $eventId = null;

    public string $title = '';

    public string $description = '';

    public string $starts_at_date = '';

    public string $starts_at_time = '';

    public string $location = '';

    #[On('open-event-modal')]
    public function openModal($eventId = null): void
    {
        $this->reset();
        $this->open = true;

        if ($eventId) {
            $this->eventId = (int) $eventId;
            $event = Event::find($this->eventId);

            if ($event) {
                $this->title = $event->title;
                $this->description = (string) ($event->description ?? '');
                $this->starts_at_date = $event->starts_at->format('Y-m-d');
                $this->starts_at_time = $event->starts_at->format('H:i');
                $this->location = (string) ($event->location ?? '');
            }
        } else {
            $this->starts_at_date = now()->format('Y-m-d');
            $this->starts_at_time = '';
        }
    }

    public function closeModal(): void
    {
        $this->open = false;
        $this->reset();
    }

    public function save(): void
    {
        $validated = $this->validate(
            [
                'title' => ['required', 'string', 'min:2', 'max:255'],
                'description' => ['nullable', 'string', 'max:10000'],
                'starts_at_date' => ['required', 'date'],
                'starts_at_time' => ['nullable', 'date_format:H:i'],
                'location' => ['nullable', 'string', 'max:255'],
            ],
            [
                'title.required' => 'Informe o título do evento.',
                'title.min' => 'O título deve ter pelo menos 2 caracteres.',
                'starts_at_date.required' => 'Informe a data.',
                'starts_at_date.date' => 'Data inválida.',
                'starts_at_time.date_format' => 'Hora inválida (use HH:MM).',
            ],
        );

        $time = ($validated['starts_at_time'] ?? '') !== '' ? $validated['starts_at_time'] : '00:00';
        $starts_at = Carbon::parse($validated['starts_at_date'].' '.$time);

        Event::updateOrCreate(
            ['id' => $this->eventId],
            [
                'title' => $validated['title'],
                'description' => ($validated['description'] ?? '') === '' ? null : $validated['description'],
                'starts_at' => $starts_at,
                'location' => ($validated['location'] ?? '') === '' ? null : $validated['location'],
            ],
        );

        $this->dispatch('event-saved');
        $this->closeModal();
    }
};
?>

<div>
    @if ($open)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="w-full max-w-2xl rounded-lg bg-white p-6" @click.away="$wire.closeModal()">
                <h1 class="mb-4 text-3xl font-bold">
                    {{ $eventId ? 'Editar Evento' : 'Novo Evento' }}
                </h1>

                <form wire:submit="save">
                    <div class="space-y-4">
                        <div>
                            <input type="text" wire:model="title" placeholder="Título do Evento"
                                class="w-full h-11 rounded-lg border border-gray-300 p-2 @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div>
                                <input type="date" wire:model="starts_at_date"
                                    class="w-full h-11 rounded-lg border border-gray-300 p-2 @error('starts_at_date') border-red-500 @enderror">
                                @error('starts_at_date')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="time" wire:model="starts_at_time"
                                    class="w-full h-11 rounded-lg border border-gray-300 p-2 @error('starts_at_time') border-red-500 @enderror">
                                @error('starts_at_time')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="text" wire:model="location" placeholder="Local (opcional)"
                                    class="w-full h-11 rounded-lg border border-gray-300 p-2 @error('location') border-red-500 @enderror">
                                @error('location')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <textarea wire:model="description" placeholder="Descrição (opcional)"
                                class="w-full h-24 rounded-lg border border-gray-300 p-2 @error('description') border-red-500 @enderror"></textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <button type="button" wire:click="closeModal" class="rounded bg-gray-300 px-4 py-2">
                            Cancelar
                        </button>
                        <button type="submit" class="rounded bg-primary px-4 py-2 text-white">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
