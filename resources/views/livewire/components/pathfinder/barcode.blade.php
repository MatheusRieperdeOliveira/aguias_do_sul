<?php

use App\Models\Pathfinder;
use App\Models\Unit;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Requirement;

new class extends Component {
    public bool $open = false;
    public ?Pathfinder $pathfinder = null;

    #[On('open-barcode-modal')]
    public function openModal($pathfinderId)
    {
        $this->pathfinder = Pathfinder::find($pathfinderId);

        $this->open = true;
    }

    public function closeModal()
    {
        $this->open = false;
    }
}?>

<div>
    @if($open)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity"
            wire:click="closeModal"
        >
            <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4 transform transition-all border border-gray-100" @click.stop>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800">Código {{ $pathfinder->name }}</span>
                </div>
                <div class="flex items-center justify-center mt-15">
                    {!! DNS2D::getBarcodeHTML("$pathfinder->id", 'QRCODE') !!}
                </div>
                <div class="mt-8 flex items-center justify-end gap-3">
                    <button wire:click="closeModal" class="flex-1 sm:flex-none px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
