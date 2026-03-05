<?php
use Livewire\Volt\Component;

new class extends Component {
    public string $title;
    public array $links;
    public string $sectionKey;
};
?>

<div x-data>
    <div class="flex items-center justify-between">
        <p class="text-sm font-semibold text-gray-500">
            {{ $title }}
        </p>
        <button @click="$store.navbar.toggle('{{ $sectionKey }}')" class="cursor-pointer p-1">
            <i data-lucide="chevron-up" class="h-5 w-5 transition-transform duration-300 ease-in-out" :class="{ 'rotate-180': !$store.navbar.items.{{ $sectionKey }} }"></i>
        </button>
    </div>

    <div x-show="$store.navbar.items.{{ $sectionKey }}" x-transition.opacity>
        @foreach ($links as $link)
            @php
                $isDisabled = str_contains($link['label'], '(Em breve)');
                $isActive = isset($link['route']) && request()->routeIs($link['route']);
            @endphp
            <a
                href="{{ $isDisabled ? '#' : (isset($link['route']) ? route($link['route']) : '#') }}"
                @class([
                    'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors',
                    'bg-primary text-primary-foreground' => $isActive,
                    'text-muted-foreground hover:bg-secondary hover:text-secondary-foreground' => !$isActive && !$isDisabled,
                    'opacity-50 cursor-not-allowed' => $isDisabled,
                ])
            >
                <i data-lucide="{{ $link['icon'] }}" class="h-5 w-5"></i>
                <p>{{ $link['label'] }}</p>
            </a>
        @endforeach
    </div>
</div>
