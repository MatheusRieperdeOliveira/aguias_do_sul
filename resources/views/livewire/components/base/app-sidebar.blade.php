<?php

use Livewire\Volt\Component;

new class extends Component {
    public array $navigation;

    public function mount(): void
    {
        $this->navigation = [
            [
                'key' => 'principal',
                'title' => 'PRINCIPAL',
                'links' => [
                    ['label' => 'Dashboard', 'icon' => 'compass', 'route' => 'home.index'],
                ],
            ],
            [
                'key' => 'gestao',
                'title' => 'GESTÃO',
                'links' => [
                    ['label' => 'Desbravadores', 'icon' => 'flame-kindling', 'route' => 'pathfinder.index'],
                    ['label' => 'Unidades', 'icon' => 'users', 'route' => 'unit.index'],
                    ['label' => 'Eventos', 'icon' => 'calendar-1', 'route' => 'event.index'],
                    ['label' => 'Requisitos', 'icon' => 'chart-no-axes-column', 'route' => 'requirement.index'],
                ],
            ],
            [
                'key' => 'pontuacoes',
                'title' => 'PONTUAÇÕES',
                'links' => [
                    ['label' => 'Desbravador', 'icon' => 'crown', 'route' => 'points.pathfinder'],
                ],
            ],
        ];
    }
};
?>

<div class="w-64 h-screen shrink-0 border-r border-border bg-card">
    <div class="flex h-full flex-col">

        <div class="flex h-16 items-center gap-3 border-b border-border px-6">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
                <i data-lucide="compass" class="h-6 w-6 text-primary-foreground"></i>
            </div>
            <div>
                <h1 class="text-lg font-semibold text-foreground">Desbravadores</h1>
                <p class="text-xs text-muted-foreground">Aguias do sul</p>
            </div>
        </div>

        <div class="flex-1 space-y-4 p-4">
            @foreach ($navigation as $section)
                <livewire:components.base.nav
                    :title="$section['title']"
                    :links="$section['links']"
                    :sectionKey="$section['key']"
                    :key="$section['key']"
                />
            @endforeach
        </div>

        <div class="border-t border-border p-4">
            <p class="text-xs text-muted-foreground text-center">
                Águias do sul
            </p>
        </div>

    </div>
</div>
