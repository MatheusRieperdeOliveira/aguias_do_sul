<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Geist+Mono:wght@100..900&family=Geist:wght@100..900&display=swap');
    </style>
    <title>Aguias do Sul</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex h-screen">
@include('components.base.app-sidebar')

<main class="flex-1 overflow-auto bg-secondary">
    @yield('content')
</main>

<script>
    lucide.createIcons();
</script>
</body>
</html>
