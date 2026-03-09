<?php

use Livewire\Volt\Component;

new class extends Component {
    public array $navigation;

    public bool $openSettingMenu = false;

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

        <div wire:show="openSettingMenu"
             class="border-t border-r border-gray-300 h-auto w-full cursor-pointer">
            <div>
                <a href="{{ route('profile.settings') }}"
                   class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-muted-foreground transition hover:bg-secondary hover:text-secondary-foreground">
                        <i data-lucide="user" class="h-4 w-4"></i>
                        Pefil
                    </button>
                </a>
            </div>
            <div>
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-muted-foreground transition hover:bg-secondary hover:text-secondary-foreground">
                        <i data-lucide="log-out" class="h-4 w-4"></i>
                        Sair
                    </button>
                </form>
            </div>
        </div>

        <div class="border border-gray-300">
            <div class="flex items-center justify-between gap-2 min-h-20 rounded-lg px-5">
                <div class="flex items-center gap-2">
                    <div
                        class="flex items-center justify-center w-10 h-10 text-center bg-blue-300 text-blue-600 rounded-full font-black">
                        <p>
                            {{strtoupper(auth()->user()?->name[0])}}
                        </p>
                        <p>
                            {{strtoupper(auth()->user()?->name[1])}}
                        </p>
                    </div>
                    <div class="text-sm font-medium text-foreground truncate">
                        {{auth()->user()?->name}}
                    </div>
                </div>

                <button x-on:click="$wire.openSettingMenu = !$wire.openSettingMenu" class="cursor-pointer">
                    <i data-lucide="settings" class="h-4 w-4 text-primary"></i>
                </button>
            </div>
        </div>

    </div>
</div>
