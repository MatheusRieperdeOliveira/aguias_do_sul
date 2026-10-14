<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Exports\PointExport;
use Maatwebsite\Excel\Facades\Excel;

new #[Layout('livewire.layouts.app')] class extends Component {
    public string $title = 'Dashboard';
    public string $description = 'Visão geral do clube de desbravadores';
    public string $icon = 'layout-dashboard';

    public int $totalPontos = 1250;
    public int $totalMembros = 45;
    public int $atividadesMes = 12;

    public function export()
    {
        return Excel::download(new PointExport(), 'relatorio_pontos.xlsx');
    }
}; ?>

<div>
    <div
        class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
    >
        <div class="min-w-0 flex-1">
            <livewire:components.base.header-page
                :title="$title"
                :description="$description"
                :icon="$icon"
            />
        </div>

        <div class="px-4 md:px-8 md:pb-0 pb-2 shrink-0">
            <button
                wire:click="export"
                class="cursor-pointer inline-flex items-center justify-center w-full md:w-auto px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary rounded-xl transition-all shadow-sm gap-2 focus:ring-2 focus:ring-primary/20 outline-none"
            >
                <i data-lucide="file-down" class="w-4 h-4 text-white"></i>
                Relatório de Pontuação
            </button>
        </div>
    </div>

    <div class="px-4 pb-6 md:px-8 md:pb-8">
        <div
            x-data="{ cards: cards }"
            class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3"
        >
            <template x-for="(card, index) in cards" :key="index">
                <div
                    class="group flex flex-col justify-between gap-6 rounded-2xl border border-border/60 bg-card p-5 transition-all duration-300 md:p-6"
                >
                    <div class="space-y-3">
                        <div class="space-y-1">
                            <h3
                                x-text="card.title"
                                class="text-xl font-semibold tracking-tight text-foreground"
                            ></h3>

                            <p class="text-sm leading-relaxed text-muted-foreground">Acesse a página de <span x-text="card.title.toLowerCase()"></span>.</p>
                        </div>
                    </div>

                    <a
                        wire:navigate
                        :href="card.link"
                        class="inline-flex w-fit items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all duration-200 hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2"
                    >
                        Acessar página

                        <i
                            data-lucide="arrow-right"
                            class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1"
                        ></i>
                    </a>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
    const cards = [
        {
            title: 'Desbravadores',
            link: 'pathfinder',
        },
        {
            title: 'Unidades',
            link: 'unit',
        },
        {
            title: 'Requisitos',
            link: 'requirements',
        },
    ];
</script>
