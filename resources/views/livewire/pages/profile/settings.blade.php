<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('livewire.layouts.app')]
class extends Component {
    public string $name = '';
    public string $email = '';
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $title = "Configurações";
    public string $description = "Atualize suas informações de perfil e senha.";
    public string $icon = "settings";

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateUserInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.',email,'.$user->id],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('saved');
    }

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('saved');
    }
}
?>

<div>
    <livewire:components.base.header-page
        :title="$title"
        :description="$description"
        :icon="$icon"
    />

    <div class="p-8 space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1">
                <h2 class="text-lg font-medium text-foreground">Informações do Perfil</h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    Atualize as informações de perfil e o endereço de e-mail da sua conta.
                </p>
            </div>
            <div class="md:col-span-2">
                <form wire:submit.prevent="updateUserInformation" class="bg-card p-6 rounded-lg shadow">
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="mb-1.5 block text-sm font-medium text-foreground">Nome</label>
                            <input
                                wire:model="name"
                                id="name"
                                type="text"
                                class="w-full rounded-lg border border-input bg-background px-3 py-2 text-foreground shadow-sm transition focus:border-ring focus:outline-none focus:ring-2 focus:ring-ring"
                                placeholder="Nome"
                            />
                            @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-medium text-foreground">Email</label>
                            <input
                                wire:model="email"
                                id="email"
                                type="email"
                                class="w-full rounded-lg border border-input bg-background px-3 py-2 text-foreground shadow-sm transition focus:border-ring focus:outline-none focus:ring-2 focus:ring-ring"
                                placeholder="Email"
                            />
                            @error('email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg shadow-sm hover:bg-primary/80 focus:outline-none focus:ring-2 focus:ring-ring">
                            Salvar
                        </button>
                        <span x-data="{ show: false }" x-show="show" x-transition x-init="() => { Livewire.on('saved', () => { show = true; setTimeout(() => show = false, 2000) }) }" class="text-sm text-muted-foreground">
                            Salvo.
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="border-t border-border"></div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1">
                <h2 class="text-lg font-medium text-foreground">Atualizar Senha</h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    Certifique-se de que sua conta esteja usando uma senha longa e aleatória para se manter segura.
                </p>
            </div>
            <div class="md:col-span-2">
                <form wire:submit.prevent="updatePassword" class="bg-card p-6 rounded-lg shadow">
                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="mb-1.5 block text-sm font-medium text-foreground">Senha Atual</label>
                            <input
                                wire:model="current_password"
                                id="current_password"
                                type="password"
                                class="w-full rounded-lg border border-input bg-background px-3 py-2 text-foreground shadow-sm transition focus:border-ring focus:outline-none focus:ring-2 focus:ring-ring"
                            />
                            @error('current_password') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="password" class="mb-1.5 block text-sm font-medium text-foreground">Nova Senha</label>
                            <input
                                wire:model="password"
                                id="password"
                                type="password"
                                class="w-full rounded-lg border border-input bg-background px-3 py-2 text-foreground shadow-sm transition focus:border-ring focus:outline-none focus:ring-2 focus:ring-ring"
                            />
                            @error('password') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-foreground">Confirmar Nova Senha</label>
                            <input
                                wire:model="password_confirmation"
                                id="password_confirmation"
                                type="password"
                                class="w-full rounded-lg border border-input bg-background px-3 py-2 text-foreground shadow-sm transition focus:border-ring focus:outline-none focus:ring-2 focus:ring-ring"
                            />
                            @error('password_confirmation') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mt-6">
                        <button type="submit" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg shadow-sm hover:bg-primary/80 focus:outline-none focus:ring-2 focus:ring-ring">
                            Salvar
                        </button>
                        <span x-data="{ show: false }" x-show="show" x-transition x-init="() => { Livewire.on('saved', () => { show = true; setTimeout(() => show = false, 2000) }) }" class="text-sm text-muted-foreground">
                            Salvo.
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
