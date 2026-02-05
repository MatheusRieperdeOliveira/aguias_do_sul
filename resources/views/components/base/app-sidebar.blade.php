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

        <nav class="flex-1 space-y-1 p-4">

            <a
                href="{{ route('home.index') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                {{ request()->routeIs('home.index')
                    ? 'bg-primary text-primary-foreground'
                    : 'text-muted-foreground hover:bg-secondary hover:text-secondary-foreground' }}"
            >
                <i data-lucide="compass" class="h-5 w-5"></i>
                <p>Dashboard</p>
            </a>

            <a
                href="{{ route('pathfinder.index') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                {{ request()->routeIs('pathfinder.index')
                    ? 'bg-primary text-primary-foreground'
                    : 'text-muted-foreground hover:bg-secondary hover:text-secondary-foreground' }}"
            >
                <i data-lucide="users" class="h-5 w-5"></i>
                <p>Desbravadores</p>
            </a>

            <a
                href="{{ route('unit.index') }}"
                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                {{ request()->routeIs('unit.index')
                    ? 'bg-primary text-primary-foreground'
                    : 'text-muted-foreground hover:bg-secondary hover:text-secondary-foreground' }}"
            >
                <i data-lucide="id-card-lanyard" class="h-5 w-5"></i>
                <p>Unidades</p>
            </a>

        </nav>

        <div class="border-t border-border p-4">
            <p class="text-xs text-muted-foreground text-center">
                Clube de Desbravadores
            </p>
        </div>

    </div>
</div>
