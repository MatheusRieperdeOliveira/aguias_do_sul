<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

new #[Layout('livewire.layouts.guest')]
class extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    public function mount(): void
    {
        if (Auth::check()) {
            $this->redirect(route('home.index'), navigate: true);
        }
    }

    public function login(): void
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            $intended = session('url.intended', route('home.index'));
            session()->forget('url.intended');
            $this->redirect($intended, navigate: true);
        }

        $this->addError('email', 'E-mail ou senha incorretos.');
    }
}; ?>

<div class="w-full max-w-sm space-y-8 rounded-2xl border border-[var(--border)] bg-[var(--card)] p-8 shadow-lg">
    <div class="flex flex-col items-center gap-2">
        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-[var(--primary)]">
            <i data-lucide="compass" class="h-8 w-8 text-[var(--primary-foreground)]"></i>
        </div>
        <h1 class="text-xl font-semibold text-[var(--foreground)]">Águias do Sul</h1>
        <p class="text-sm text-[var(--muted-foreground)]">Entre na sua conta</p>
    </div>

    <form wire:submit="login" class="space-y-5">
        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-[var(--foreground)]">E-mail</label>
            <input
                wire:model="email"
                id="email"
                type="email"
                autocomplete="email"
                autofocus
                class="w-full rounded-lg border border-[var(--input)] bg-[var(--background)] px-3 py-2 text-[var(--foreground)] shadow-sm transition focus:border-[var(--ring)] focus:outline-none focus:ring-2 focus:ring-[var(--ring)]"
                placeholder="seu@email.com"
            />
            @error('email')
                <p class="mt-1 text-sm text-[var(--destructive)]">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-1.5 block text-sm font-medium text-[var(--foreground)]">Senha</label>
            <input
                wire:model="password"
                id="password"
                type="password"
                autocomplete="current-password"
                class="w-full rounded-lg border border-input bg-background px-3 py-2 text-foreground shadow-sm transition focus:border-ring focus:outline-none focus:ring-2 focus:ring-ring"
                placeholder="••••••••"
            />

            @error('password')
                <p class="mt-1 text-sm text-destructive">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <input
                wire:model="remember"
                id="remember"
                type="checkbox"
                class="h-4 w-4 rounded border-input text-primary focus:ring-ring"
            />
            <label for="remember" class="ml-2 text-sm text-muted-foreground">Lembrar de mim</label>
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-medium text-primary-foreground shadow transition hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-[var(--ring)] focus:ring-offset-2 cursor-pointer"
        >
            Entrar
        </button>
    </form>
</div>
