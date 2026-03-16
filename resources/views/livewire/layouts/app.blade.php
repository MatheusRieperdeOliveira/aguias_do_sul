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
<body class="flex h-screen">
@persist('sidebar')
    <livewire:components.base.app-sidebar />
@endpersist

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
