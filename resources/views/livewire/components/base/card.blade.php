<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Requirement;

new #[Layout('layouts.app')]
class extends Component {
    public Requirement $requirement;
}

?>

<div class="flex flex-col items-center w-full p-3 border-2 border-primary text-primary-foreground rounded-lg space-y-5">
    <div class="flex items-center justify-between w-full">
            <p class="text-black">
                {{$requirement->title}}
            </p>
        <div class="p-3 bg-secondary flex items-center justify-center w-10 h-10 text-secondary-foreground rounded-xl">
            <p>
                {{$requirement->score}}
            </p>
        </div>
    </div>
    <div class="flex items-center justify-end w-full">
        <button
            wire:click="$dispatch('open-modal-scan-qrcode', { requirementId: {{$requirement->id}} })"
            class="flex items-center gap-1 bg-primary p-2 rounded-xl cursor-pointer">
            <i data-lucide="qr-code" class="w-5 h-5"></i>
            <p>
                ler qrcode
            </p>
        </button>
    </div>
</div>

