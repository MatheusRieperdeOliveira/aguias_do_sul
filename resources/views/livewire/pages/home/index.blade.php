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
    public string $icon = 'layout-dashboard';

    public int $totalPontos = 1250;
    public int $totalMembros = 45;
    public int $atividadesMes = 12;

    public function export()
    {
        return Excel::download(new PointExport, 'relatorio_pontos.xlsx');
    }
}; ?>

<div class="space-y-8 p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <livewire:components.base.header-page
            :title="$title"
            :description="$description"
            :icon="$icon"
        />

        <div class="flex items-center gap-3">
            <button
                wire:click="export"
                class="cursor-pointer inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary rounded-xl transition-all shadow-sm gap-2 focus:ring-2 focus:ring-primary/20 outline-none"
            >
                <i data-lucide="file-down" class="w-4 h-4 text-white"></i>
                Relatório de Pontuação
            </button>
        </div>
    </div>

    <div class="bg-card border border-border/40 rounded-3xl p-8 min-h-[300px] flex flex-col items-center justify-center text-center space-y-4">
        <div class="bg-muted p-4 rounded-full">
            <i data-lucide="binary" class="w-12 h-12 text-muted-foreground/50"></i>
        </div>
        <div>
            <h4 class="text-xl font-semibold">Em breve</h4>
            <p class="text-muted-foreground max-w-sm mx-auto">
                Acompanhe os pontos de cada desbravador por aqui.
            </p>
        </div>
    </div>
</div>