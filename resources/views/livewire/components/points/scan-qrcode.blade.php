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
        if ($pathfinderId === "" || $pathfinderId == 0) return;

        $pathfinder = Pathfinder::find($pathfinderId);

        if(! $pathfinder) return;

        if(in_array($pathfinder, $this->pathfinderScans))
            //notificar que já existe foi scaneado;
            return;

        $this->pathfinderScans[] = $pathfinder;
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

                <input class="w-full h-10 border border-gray-400 rounded-sm pl-3" type="number" id="qr" oninput="handleInput(this.value)" placeholder="Escanear QR Code" autofocus>

                <div class="flex flex-col justify-start gap-2 w-full max-h-180 px-2 py-3 overscroll-contain overflow-y-auto">
                    @if($pathfinderScans)
                        @foreach($pathfinderScans as $pathfinderScan)
                            <div class="flex items-center justify-start gap-2 border border-black min-h-20 rounded-lg px-5">
                                <div class="flex items-center justify-center w-10 h-10 text-center bg-blue-300 text-blue-600 rounded-full w-10 font-black">
                                    <p>
                                        {{strtoupper($pathfinderScan->name[0])}}
                                    </p>
                                    <p>
                                        {{strtoupper($pathfinderScan->name[1])}}
                                    </p>
                                </div>
                                <div>
                                    {{$pathfinderScan->name}}
                                </div>

                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="mt-8 grid grid-cols-2 gap-3">
                    <button wire:click="closeModal" class="cursor-pointer flex items-center justify-center gap-2 sm:flex-none px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                        <p>
                            Cancelar
                        </p>
                    </button>
                    <button wire:click="save" class="cursor-pointer flex items-center justify-center gap-2 sm:flex-none px-5 py-2.5 text-sm font-semibold text-white bg-primary rounded-xl transition-colors">
                        <i data-lucide="check" class="w-5 h-5"></i>
                        <p>
                            Salvar
                        </p>
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
