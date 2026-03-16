<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Point;
use App\Exports\PointExport;
use Maatwebsite\Excel\Facades\Excel;

new #[Layout('livewire.layouts.app')]
class extends Component
{
    public string $title = 'Dashboard';
    public string $description = 'Visão geral do clube de desbravadores';
    public string $icon = 'home';

    public function export()
    {
        dd(Point::with(['pathfinder', 'requirement'])->get()->toArray());   
        return Excel::download(new PointExport, 'points.xlsx');
    }
}; ?>

<div>
    <livewire:components.base.header-page
        :title="$title"
        :description="$description"
        :icon="$icon"
    />

    <button
        wire:click="export"
        class="cursor-pointer flex items-center bg-primary text-primary-foreground h-10 px-4 rounded-lg">
        <i data-lucide="plus" class="mr-2 h-4 w-4"></i>
        <span>Exportar</span>
    </button>
</div>
