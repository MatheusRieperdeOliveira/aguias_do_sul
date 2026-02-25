<?php

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[Layout('layouts.app')]
class extends Component {
    public Carbon $currentDate;
    public ?string $selectedDate = null;

    public function mount(): void
    {
        $this->currentDate = Carbon::now();
    }

    #[Computed]
    public function days(): Collection
    {
        $startOfMonth = $this->currentDate->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
        $endOfMonth = $this->currentDate->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

        $groupedEvents = Event::whereBetween('starts_at', [$startOfMonth, $endOfMonth])
            ->orderBy('starts_at')
            ->get()
            ->groupBy(fn($event) => $event->starts_at->format('Y-m-d'));

        $days = collect();
        $date = $startOfMonth->copy();

        while ($date->lte($endOfMonth)) {
            $days->push([
                'date' => $date->copy(),
                'is_current_month' => $date->month === $this->currentDate->month,
                'events' => $groupedEvents->get($date->format('Y-m-d'), collect()),
            ]);
            $date->addDay();
        }
        return $days;
    }

    #[Computed]
    public function selectedDayEvents(): Collection
    {
        if (!$this->selectedDate) {
            return collect();
        }
        return Event::whereDate('starts_at', $this->selectedDate)->orderBy('starts_at')->get();
    }

    #[On('event-saved')]
    public function refresh(): void
    {
        // Re-renders the component
    }

    public function openDayModal(string $date): void
    {
        $this->selectedDate = $date;
    }

    public function closeDayModal(): void
    {
        $this->selectedDate = null;
    }

    public function goToPreviousMonth(): void
    {
        $this->currentDate->subMonth();
    }

    public function goToNextMonth(): void
    {
        $this->currentDate->addMonth();
    }

    public function goToCurrentMonth(): void
    {
        $this->currentDate = Carbon::now();
    }

    public function toggleStatus(int $eventId): void
    {
        $event = Event::find($eventId);
        if ($event) {
            $event->status = $event->status === 'pending' ? 'completed' : 'pending';
            $event->save();
        }
    }

    public function deleteEvent(int $eventId): void
    {
        Event::destroy($eventId);
        // If the last event of the day is deleted, close the modal
        if ($this->selectedDayEvents()->isEmpty()) {
            $this->closeDayModal();
        }
    }
};
?>

<div>
    <!-- Include the form modal -->
    <livewire:components.event.form />

    <!-- Header -->
    <div class="flex items-center justify-between w-full p-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Calendário de Eventos</h1>
            <p class="text-muted-foreground mt-1">Gerencie os eventos do clube</p>
        </div>
        <button wire:click="$dispatch('open-event-modal')"
            class="cursor-pointer flex items-center bg-primary text-primary-foreground h-10 px-4 rounded-lg">
            <i data-lucide="calendar-plus" class="mr-2 h-4 w-4"></i>
            <span>Novo Evento</span>
        </button>
    </div>

    <!-- Calendar -->
    <div class="p-8">
        <!-- Calendar Controls -->
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold">{{ $currentDate->translatedFormat('F Y') }}</h2>
            <div class="flex items-center gap-2">
                <button wire:click="goToPreviousMonth" class="p-2 rounded-md hover:bg-gray-200">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </button>
                <button wire:click="goToCurrentMonth" class="p-2 rounded-md hover:bg-gray-200 text-sm">Hoje</button>
                <button wire:click="goToNextMonth" class="p-2 rounded-md hover:bg-gray-200">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-7 border-l border-gray-200">
            @foreach (['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'] as $day)
                <div class="text-center text-sm font-medium text-gray-500 py-2 border-b border-gray-200">{{ $day }}</div>
            @endforeach

            @foreach ($this->days as $day)
                <div @class([
                    'relative h-36 border-b border-r border-gray-200 p-2 flex flex-col transition-colors cursor-pointer',
                    'bg-gray-50 text-gray-400' => !$day['is_current_month'],
                    'bg-white hover:bg-gray-50' => $day['is_current_month'],
                ]) @if($day['is_current_month']) wire:click="openDayModal('{{ $day['date']->toDateString() }}')" @endif>
                    <span @class([
                        'font-semibold' => $day['date']->isToday(),
                        'text-primary' => $day['date']->isToday(),
                        'bg-primary w-10 text-center text-white rounded-full' => $day['date']->isToday(),
                    ])>{{ $day['date']->day }}</span>

                    <div class="mt-1 flex-1 overflow-hidden space-y-1">
                        @foreach ($day['events'] as $event)
                            <div @class([
                                'flex items-center rounded px-1 text-xs text-white',
                                'bg-blue-500' => $event->status === 'pending',
                                'bg-green-500' => $event->status === 'completed',
                            ])>
                                <p class="flex-1 truncate">{{ $event->title }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if ($selectedDate)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60" x-data="{ open: @entangle('selectedDate') }" x-show="open" x-on:keydown.escape.window="open = null">
            <div class="w-full max-w-lg rounded-lg bg-white p-6" @click.away="open = null">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d \d\e F') }}</h2>
                    <button wire:click="closeDayModal" class="p-2 rounded-full hover:bg-gray-200">
                        <i data-lucide="x" class="w-5 h-5 cursor-pointer"></i>
                    </button>
                </div>

                <div class="space-y-4 max-h-[60vh] overflow-y-auto">
                    @forelse ($this->selectedDayEvents as $event)
                        <div class="p-4 border rounded-lg flex items-start gap-4">
                            <span @class([
                                'w-3 h-3 rounded-full mt-1.5 shrink-0',
                                'bg-blue-500' => $event->status === 'pending',
                                'bg-green-500' => $event->status === 'completed',
                            ])></span>
                            <div class="flex-1">
                                <p class="font-bold text-gray-800">{{ $event->title }}</p>
                                <p class="text-sm text-gray-600">
                                    <i data-lucide="clock" class="inline w-3 h-3"></i> {{ $event->starts_at->format('H:i') }}
                                    @if($event->location)
                                        <span class="mx-2">|</span>
                                        <i data-lucide="map-pin" class="inline w-3 h-3"></i> {{ $event->location }}
                                    @endif
                                </p>
                                @if($event->description)
                                    <p class="mt-2 text-sm text-gray-700">{{ $event->description }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-1">
                                <button wire:click="toggleStatus({{ $event->id }})" class="p-2 hover:bg-gray-200 rounded-full" title="Mudar Status">
                                    @if($event->status === 'pending')
                                        <i data-lucide="check-circle-2" class="w-5 h-5 text-green-600"></i>
                                    @else
                                        <i data-lucide="x-circle" class="w-5 h-5 text-blue-600"></i>
                                    @endif
                                </button>
                                <button wire:click="$dispatch('open-event-modal', { eventId: {{ $event->id }} }); closeDayModal()" class="p-2 hover:bg-gray-200 rounded-full" title="Editar">
                                    <i data-lucide="pencil" class="w-5 h-5 text-blue-600"></i>
                                </button>
                                <button wire:click="deleteEvent({{ $event->id }})" wire:confirm="Tem certeza que deseja excluir este evento?" class="p-2 hover:bg-gray-200 rounded-full" title="Excluir">
                                    <i data-lucide="trash-2" class="w-5 h-5 text-red-600"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Nenhum evento para este dia.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>
