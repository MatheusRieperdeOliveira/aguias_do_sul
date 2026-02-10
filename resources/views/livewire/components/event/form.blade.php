<?php

use App\Models\Event;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;
use Carbon\Carbon;

new class extends Component {
    public bool $open = false;
    public ?int $eventId = null;

    #[Rule('required|string|max:255')]
    public string $title = '';

    #[Rule('nullable|string')]
    public string $description = '';

    #[Rule('required|date')]
    public string $starts_at_date = '';

    #[Rule('nullable|date_format:H:i')]
    public string $starts_at_time = '';

    #[Rule('nullable|string|max:255')]
    public string $location = '';

    #[On('open-event-modal')]
    public function openModal($eventId = null)
    {
        $this->reset();
        $this->open = true;

        if ($eventId) {
            $this->eventId = $eventId;
            $event = Event::find($eventId);
            if ($event) {
                $this->title = $event->title;
                $this->description = $event->description;
                $this->starts_at_date = $event->starts_at->format('Y-m-d');
                $this->starts_at_time = $event->starts_at->format('H:i');
                $this->location = $event->location;
            }
        } else {
            $this->starts_at_date = now()->format('Y-m-d');
        }
    }

    public function closeModal()
    {
        $this->open = false;
        $this->reset();
    }

    public function save()
    {
        $this->validate();

        $starts_at = Carbon::parse($this->starts_at_date . ' ' . $this->starts_at_time);

        Event::updateOrCreate(
            ['id' => $this->eventId],
            [
                'title' => $this->title,
                'description' => $this->description,
                'starts_at' => $starts_at,
                'location' => $this->location,
            ]
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
                                class="w-full h-11 rounded-lg border border-gray-300 p-2">
                            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div>
                                <input type="date" wire:model="starts_at_date"
                                    class="w-full h-11 rounded-lg border border-gray-300 p-2">
                                @error('starts_at_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <input type="time" wire:model="starts_at_time"
                                    class="w-full h-11 rounded-lg border border-gray-300 p-2">
                                @error('starts_at_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                             <div>
                                <input type="text" wire:model="location" placeholder="Local"
                                    class="w-full h-11 rounded-lg border border-gray-300 p-2">
                                @error('location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <textarea wire:model="description" placeholder="Descrição (opcional)"
                                class="w-full h-24 rounded-lg border border-gray-300 p-2"></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
