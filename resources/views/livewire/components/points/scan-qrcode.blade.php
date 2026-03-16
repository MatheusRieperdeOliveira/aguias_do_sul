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
    public array $pathfinderScans = [];

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
            return;

        $this->pathfinderScans[] = $pathfinder;
    }

    public function save(): void
    {
        $data = collect($this->pathfinderScans)
            ->map(fn ($pathfinder) => [
                'requirement_id' => $this->requirementId,
                'pathfinder_id' => $pathfinder->id,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

        DB::table('points')->insert($data);

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->requirementId = 0;
        $this->pathfinderScans = [];
        $this->open = false;
    }
}?>

<div>
    @if($open)
        <div
            class="fixed inset-0 z-50 flex justify-end bg-slate-900/50 backdrop-blur-sm transition-opacity"
            wire:click="closeModal"
        >
            <div
                class="bg-white h-full w-full max-w-md shadow-2xl flex flex-col border-l border-gray-200 transform transition-transform duration-300 translate-x-0"
                @click.stop
            >
                <div class="flex items-center justify-between p-6 border-b border-gray-100">
                    <h3 class="font-bold text-xl text-gray-800 tracking-tight">Escanear QR Code</h3>
                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-blue-100 text-blue-800">ID: {{ $this->requirementId }}</span>
                </div>

                <div class="flex-1 p-6 overflow-y-auto">
                    <input class="w-full h-10 border border-gray-400 rounded-sm pl-3 mb-6" type="number" id="qr" oninput="handleInput(this.value)" placeholder="Escanear QR Code" autofocus>

                    <div class="flex flex-col gap-2 w-full">
                        @if($pathfinderScans)
                            @foreach($pathfinderScans as $pathfinderScan)
                                <div class="flex items-center justify-start gap-2 border border-black min-h-20 rounded-lg px-5">
                                    <div class="flex items-center justify-center w-10 h-10 text-center bg-blue-300 text-blue-600 rounded-full font-black">
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
                </div>

                <div class="p-6 border-t border-gray-100 bg-gray-50 mt-auto">
                    <div class="grid grid-cols-2 gap-3">
                        <button
                            wire:click="closeModal"
                            wire:confirm="Deseja realmente sair? Todos os registros scaneados serão removidos"
                            class="cursor-pointer flex items-center justify-center gap-2 sm:flex-none px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                            <p>Cancelar</p>
                        </button>
                        <button wire:click="save" class="cursor-pointer flex items-center justify-center gap-2 sm:flex-none px-5 py-2.5 text-sm font-semibold text-white bg-primary rounded-xl transition-colors">
                            <i data-lucide="check" class="w-5 h-5"></i>
                            <p>Salvar</p>
                        </button>
                    </div>
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
