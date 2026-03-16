<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="icon" type="image/png" href="../../../../storage/app/public/favicon.ico">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Geist:wght@100..900&display=swap">
    <title>Entrar - Águias do Sul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col items-center justify-center bg-[var(--background)]">
    {{ $slot }}
    @livewireScripts
    <script>lucide.createIcons();</script>
</body>
</html>
