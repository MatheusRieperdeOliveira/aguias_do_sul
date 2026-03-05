@php
    $navigation = [
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
                ['label' => 'Desbravadores', 'icon' => 'users', 'route' => 'pathfinder.index'],
                ['label' => 'Unidades', 'icon' => 'flag', 'route' => 'unit.index'],
                ['label' => 'Eventos', 'icon' => 'calendar', 'route' => 'event.index'],
                ['label' => 'Requisitos', 'icon' => 'chart-no-axes-column', 'route' => 'requirement.index'],
                ['label' => 'Presença (Em breve)', 'icon' => 'bookmark-check'],
                ['label' => 'Especialidade (Em breve)', 'icon' => 'award'],
            ],
        ],
        [
            'key' => 'pontuacoes',
            'title' => 'PONTUAÇÕES',
            'links' => [
                ['label' => 'Desbravador', 'icon' => 'calendar', 'route' => 'points.pathfinder'],
                ['label' => 'Unidade (Em breve)', 'icon' => 'calendar'],
            ],
        ],
    ];
@endphp

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

        <nav class="flex-1 space-y-4 p-4">
            @foreach ($navigation as $section)
                <livewire:components.base.nav
                    :title="$section['title']"
                    :links="$section['links']"
                    :sectionKey="$section['key']"
                    :key="$section['key']"
                />
            @endforeach
        </nav>

        <div class="border-t border-border p-4">
            <p class="text-xs text-muted-foreground text-center">
                Clube de Desbravadores
            </p>
        </div>

    </div>
</div>
