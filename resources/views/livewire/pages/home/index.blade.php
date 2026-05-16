<?php

use App\Exports\PointExport;
use App\Models\Pathfinder;
use App\Models\Point;
use App\Models\Requirement;
use App\Models\Unit;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Maatwebsite\Excel\Facades\Excel;

new #[Layout('livewire.layouts.app')]
class extends Component {
    public string $title = 'Dashboard';

    public string $description = 'Visão geral do clube de desbravadores';

    public string $icon = 'layout-dashboard';

    #[Computed]
    public function unitsCount(): int
    {
        return Unit::query()->count();
    }

    #[Computed]
    public function requirementsCount(): int
    {
        return Requirement::query()->count();
    }

    #[Computed]
    public function topPathfinders()
    {
        return Pathfinder::query()
            ->with('unit')
            ->has('points')
            ->select('pathfinders.*')
            ->selectRaw('(
                select coalesce(sum(requirements.score), 0)
                from points
                inner join requirements on requirements.id = points.requirement_id
                where points.pathfinder_id = pathfinders.id
            ) as total_score')
            ->orderByDesc('total_score')
            ->orderBy('pathfinders.name')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function recentPoints()
    {
        return Point::query()
            ->with(['pathfinder', 'requirement'])
            ->orderByDesc('points.created_at')
            ->limit(8)
            ->get();
    }

    public function export()
    {
        return Excel::download(new PointExport, 'relatorio_pontos.xlsx');
    }
};
?>

<div class="space-y-8 p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <livewire:components.base.header-page
            :title="$title"
            :description="$description"
            :icon="$icon"
        />

        <div class="flex items-center gap-3">
            <button
                type="button"
                wire:click="export"
                class="cursor-pointer inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary rounded-xl transition-all shadow-sm gap-2 focus:ring-2 focus:ring-primary/20 outline-none"
            >
                <i data-lucide="file-down" class="w-4 h-4 text-white"></i>
                Relatório de Pontuação
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('unit.index') }}" wire:navigate
            class="bg-card border border-border/40 rounded-2xl p-5 shadow-sm flex items-center justify-between gap-4 hover:border-primary/30 transition-colors">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Unidades</p>
                <p class="text-2xl font-bold text-foreground mt-1 tabular-nums">{{ $this->unitsCount }}</p>
            </div>
            <i data-lucide="chevron-right" class="w-5 h-5 text-muted-foreground"></i>
        </a>
        <a href="{{ route('requirement.index') }}" wire:navigate
            class="bg-card border border-border/40 rounded-2xl p-5 shadow-sm flex items-center justify-between gap-4 hover:border-primary/30 transition-colors">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">Requisitos</p>
                <p class="text-2xl font-bold text-foreground mt-1 tabular-nums">{{ $this->requirementsCount }}</p>
            </div>
            <i data-lucide="chevron-right" class="w-5 h-5 text-muted-foreground"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-card border border-border/40 rounded-3xl p-6 shadow-sm min-h-[280px]">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h3 class="text-lg font-semibold text-foreground">Top desbravadores</h3>
                <a href="{{ route('pathfinder.index') }}" wire:navigate class="text-sm font-medium text-primary hover:underline">Ver todos</a>
            </div>
            @if ($this->topPathfinders->isEmpty())
                <p class="text-sm text-muted-foreground py-8 text-center">Ainda não há pontuação registrada.</p>
            @else
                <ul class="space-y-3">
                    @foreach ($this->topPathfinders as $index => $pf)
                        <li class="flex items-center gap-3 p-3 rounded-xl bg-muted/40 border border-border/30">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/15 text-sm font-bold text-primary tabular-nums shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-foreground truncate">{{ $pf->name }}</p>
                                <p class="text-xs text-muted-foreground truncate">{{ $pf->unit->name ?? 'Sem unidade' }}</p>
                            </div>
                            <span class="text-sm font-bold text-primary tabular-nums shrink-0">
                                {{ number_format((int) $pf->total_score, 0, ',', '.') }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="bg-card border border-border/40 rounded-3xl p-6 shadow-sm min-h-[280px]">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h3 class="text-lg font-semibold text-foreground">Últimos lançamentos</h3>
                <a href="{{ route('points.pathfinder') }}" wire:navigate class="text-sm font-medium text-primary hover:underline">Pontuar</a>
            </div>
            @if ($this->recentPoints->isEmpty())
                <p class="text-sm text-muted-foreground py-8 text-center">Nenhum ponto registrado ainda.</p>
            @else
                <ul class="divide-y divide-border/50">
                    @foreach ($this->recentPoints as $pt)
                        <li class="py-3 flex flex-col gap-0.5 first:pt-0">
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-medium text-foreground truncate">{{ $pt->pathfinder->name ?? '—' }}</span>
                                <span class="text-xs font-semibold text-primary shrink-0 tabular-nums">+{{ $pt->requirement->score ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-2 text-xs text-muted-foreground">
                                <span class="truncate">{{ $pt->requirement->title ?? '—' }}</span>
                                <span class="shrink-0">{{ $pt->created_at->translatedFormat('d/m H:i') }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
