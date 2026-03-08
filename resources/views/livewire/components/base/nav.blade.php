<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Route;

new class extends Component {
    public string $title = '';
    public array $links = [];
    public string $sectionKey = '';
};
?>

<div x-data="{ currentUrl: window.location.href.split('?')[0] }" x-on:livewire:navigated.document="currentUrl = window.location.href.split('?')[0]">
    <div class="flex items-center justify-between">
        <p class="text-sm font-semibold text-gray-500">
            {{ $title }}
        </p>
        <button @click="$store.navbar.toggle('{{ $sectionKey }}')" class="cursor-pointer p-1">
            <i data-lucide="chevron-down" class="h-5 w-5 transition-transform duration-300 ease-in-out"
               :class="{ 'rotate-180': !$store.navbar.items.{{ $sectionKey }} }"></i>
        </button>
    </div>

    <nav x-show="$store.navbar.items.{{ $sectionKey }}" x-transition.opacity>
        @foreach ($links as $link)
            @php
                $label = $link['label'] ?? '';
                $isDisabled = str_contains($label, '(Em breve)');
                $routeName = $link['route'] ?? null;
                $routeUrl = ($routeName && Route::has($routeName)) ? route($routeName) : '#';
            @endphp
            <a wire:navigate
               href="{{ $isDisabled ? '#' : $routeUrl }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors"
               :class="{
                   'bg-primary text-primary-foreground': currentUrl === '{{ $routeUrl }}',
                   'text-muted-foreground hover:bg-secondary hover:text-secondary-foreground': currentUrl !== '{{ $routeUrl }}' && !{{ $isDisabled ? 'true' : 'false' }},
                   'opacity-50 cursor-not-allowed': {{ $isDisabled ? 'true' : 'false' }}
               }"
            >
                <i data-lucide="{{ $link['icon'] ?? 'circle' }}" class="h-5 w-5"></i>
                <p>{{ $label }}</p>
            </a>
        @endforeach
    </nav>
</div>
