<?php

use App\Models\Pathfinder;
use App\Models\Unit;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Requirement;

new class extends Component {
    public bool $open = true;
    public int $requirementId = 0;
    public $pathfinderScans = [];

    #[On('open-modal-scan-qrcode')]
    public function openModal($requirementId = null)
    {
        $this->requirementId = $requirementId;
        $this->open = true;
    }

    #[On('scan-qrcode')]
    public function scanQrcode($pathfinderId): void
    {
        if ($pathfinderId === "") return;

        $this->pathfinderScans[] = Pathfinder::find($pathfinderId);
    }

    public function save(): void
    {
        $pathfinders = Pathfinder::query()->whereIn('id', explode(', ', $this->pathfinderScan))->get();

        dd($pathfinders->toArray());
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
                    <h3 class="font-bold text-xl text-gray-800 tracking-tight">Escanear QR Code</h3>
                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800">ID: {{ $this->requirementId }}</span>
                </div>

                <input class="w-full h-10 border border-gray-400 rounded-sm" type="number" id="qr" oninput="handleInput(this.value)" autofocus>

                <div class="grid grid-cols-1 w-full bg-red-600 h-180 px-2 py-3">
                    @foreach($pathfinderScans as $pathfinderScan)
                        <div class="bg-green-600 h-15">
                            {{$pathfinderScan->name}}
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex items-center justify-end gap-3">
                    <button wire:click="closeModal" class="cursor-pointer flex-1 sm:flex-none px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="save" class="cursor-pointer flex-1 sm:flex-none px-5 py-2.5 text-sm font-semibold text-white bg-primary rounded-xl transition-colors">
                        Salvar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
<script>
    function handleInput(pathfinderId){
        Livewire.dispatch('scan-qrcode', { pathfinderId: pathfinderId })

        document.getElementById('qr').value = '';
    }
</script>
