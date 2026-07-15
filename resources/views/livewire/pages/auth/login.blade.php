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

<div class="relative flex items-center justify-center w-full h-screen overflow-hidden">

    <div
        class="absolute inset-0 bg-cover bg-center bg-no-repeat blur-xs scale-110"
        style="
            background-image: url('/icon.jpg');
            opacity: .18;
        "
    ></div>

    <div class="absolute inset-0 bg-white/10"></div>

    <div class="relative z-10 w-full max-w-md">
        <form wire:submit="login" class="space-y-5 rounded-2xl bg-white/70 backdrop-blur-md p-8 shadow-2xl">

            <div>
                <label
                    for="email"
                    class="mb-1.5 block text-sm font-medium text-[var(--foreground)]"
                >
                    E-mail
                </label>

                <input
                    wire:model="email"
                    id="email"
                    type="email"
                    autocomplete="email"
                    autofocus
                    class="w-full rounded-lg border border-[var(--input)] bg-white/90 px-3 py-2 shadow-sm"
                    placeholder="seu@email.com"
                />

                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label
                    for="password"
                    class="mb-1.5 block text-sm font-medium text-[var(--foreground)]"
                >
                    Senha
                </label>

                <input
                    wire:model="password"
                    id="password"
                    type="password"
                    autocomplete="current-password"
                    class="w-full rounded-lg border border-[var(--input)] bg-white/90 px-3 py-2 shadow-sm"
                    placeholder="••••••••"
                />

                @error('password')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input
                    wire:model="remember"
                    id="remember"
                    type="checkbox"
                    class="h-4 w-4"
                />

                <label
                    for="remember"
                    class="ml-2 text-sm text-gray-700"
                >
                    Lembrar de mim
                </label>
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-zinc-800 py-3 text-white transition hover:bg-zinc-700"
            >
                Entrar
            </button>

        </form>
    </div>

</div>