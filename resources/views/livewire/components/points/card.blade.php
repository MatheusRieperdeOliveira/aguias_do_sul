<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Requirement;

new #[Layout('layouts.app')]
class extends Component {
    public Requirement $requirement;
}

?>

<div class="flex flex-col items-center w-full p-3 rounded-sm space-y-5 shadow-xl/30">
    <div class="flex items-center justify-between w-full">
        <h3 class="text-lg font-semibold text-slate-800 tracking-tight">
            {{$requirement->title}}
        </h3>
        <div class="flex items-center gap-1.5 bg-gradient-to-r from-amber-400 to-amber-500 text-white px-3 py-1.5 rounded-full shadow-md">
            <i data-lucide="crown" class="w-3 h-3"></i>
            <p>
                {{$requirement->score}}
            </p>
        </div>
    </div>
    <div class="grid grid-cols-1 w-full">
        <button
            wire:click="$dispatch('open-modal-scan-qrcode', { requirementId: {{$requirement->id}} })"
            class="flex items-center justify-center gap-1 bg-primary p-2 rounded-xl cursor-pointer text-white">
            <i data-lucide="qr-code" class="w-5 h-5"></i>

        </button>
    </div>
</div>

