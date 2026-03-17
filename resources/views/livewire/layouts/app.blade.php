<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('storage/favicon.ico') }}" type="image/jpeg">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Geist:wght@100..900&display=swap">
    <title>Aguias do Sul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="flex flex-col md:flex-row h-screen overflow-hidden bg-background" x-data="{ sidebarOpen: false }">

<header class="flex shrink-0 h-16 items-center justify-between border-b border-border bg-card px-4 md:hidden">
    <div class="flex items-center gap-2">
        <div class="flex h-8 w-8 items-center justify-center rounded bg-primary">
            <i data-lucide="compass" class="h-5 w-5 text-primary-foreground"></i>
        </div>
        <span class="font-semibold text-foreground">Águias do Sul</span>
    </div>
    <button @click="sidebarOpen = true" class="p-2 text-foreground">
        <i data-lucide="menu" class="h-6 w-6"></i>
    </button>
</header>

<div x-show="sidebarOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-black/50 md:hidden"
     style="display: none;">
</div>

<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
     class="fixed inset-y-0 left-0 transform transition duration-300 ease-in-out md:static md:translate-x-0 shrink-0">
    @persist('sidebar')
        <livewire:components.base.app-sidebar />
    @endpersist
</div>

<main class="flex-1 overflow-auto bg-white">
    {{$slot}}
</main>

@livewireScripts

<script>
    lucide.createIcons();

    document.addEventListener('livewire:navigated', () => {
        lucide.createIcons();
    });

    document.addEventListener('livewire:init', () => {
        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            succeed(({ snapshot, effect }) => {
                setTimeout(() => {
                    lucide.createIcons();
                }, 0);
            });
        });
    });

</script>

</body>
</html>
