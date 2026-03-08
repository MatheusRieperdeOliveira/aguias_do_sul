<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Requirement;

new #[Layout('layouts.app')]
class extends Component {
    public Requirement $requirement;
}

?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow duration-200 flex flex-col justify-between h-full">
    <div class="flex justify-between items-start mb-4">
        <h3 class="text-lg font-bold text-gray-800 leading-tight pr-4 line-clamp-2" title="{{$requirement->title}}">
            {{$requirement->title}}
        </h3>
        <div class="flex items-center gap-1.5 bg-gradient-to-r from-amber-400 to-amber-500 text-white px-3 py-1 rounded-full shadow-sm shrink-0">
            <i data-lucide="crown" class="w-3.5 h-3.5"></i>
            <span class="font-bold text-sm">{{$requirement->score}}</span>
        </div>
    </div>

    <div class="mt-auto">
        <button
            wire:click="$dispatch('open-modal-scan-qrcode', { requirementId: {{$requirement->id}} })"
            class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-white py-2.5 px-4 rounded-lg transition-all duration-200 font-medium shadow-sm group active:scale-[0.98]"
        >
            <i data-lucide="qr-code" class="w-5 h-5 group-hover:scale-110 transition-transform duration-200"></i>
            <span>Escanear</span>
        </button>
    </div>
</div>
