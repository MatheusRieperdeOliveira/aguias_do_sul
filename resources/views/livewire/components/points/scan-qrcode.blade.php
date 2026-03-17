<?php

use App\Models\Pathfinder;
use App\Models\Unit;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Requirement;
use Illuminate\Support\Facades\DB;

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
        if ($pathfinderId === '' || $pathfinderId == 0) {
            return;
        }

        $pathfinder = Pathfinder::find($pathfinderId);

        if (!$pathfinder) {
            return;
        }

        $exists = collect($this->pathfinderScans)->contains('id', $pathfinder->id);
        
        if ($exists) {
            return;
        }

        $this->pathfinderScans[] = $pathfinder;
    }

    public function save(): void
    {
        if (empty($this->pathfinderScans) || $this->requirementId == 0) {
            $this->closeModal();
            return;
        }

        $data = collect($this->pathfinderScans)
            ->map(
                fn($pathfinder) => [
                    'requirement_id' => $this->requirementId,
                    'pathfinder_id' => $pathfinder->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            )
            ->toArray();

        DB::table('points')->insert($data);

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->requirementId = 0;
        $this->pathfinderScans = [];
        $this->open = false;
        $this->dispatch('stop-qr-scanner');
    }

    public function removePathfinderScan($pathfinderId): void
    {
        $this->pathfinderScans = array_filter($this->pathfinderScans, fn(Pathfinder $pathfinder) => $pathfinder->id !== $pathfinderId);
    }
}; ?>

<div>
    @if ($open)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" wire:transition.opacity>
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="closeModal"></div>

            <div class="relative bg-white w-full max-w-5xl h-[90vh] md:h-[80vh] shadow-2xl rounded-3xl overflow-hidden flex flex-col md:flex-row border border-white/20 transform transition-all" @click.stop>
                
                <div class="w-full md:w-3/5 bg-slate-50 relative flex flex-col border-b md:border-b-0 md:border-r border-gray-100">
                    <div class="p-6 md:p-8 flex flex-col gap-1">
                        <div class="flex items-center gap-3 mb-1">
                            <div class="p-2 bg-primary/10 rounded-xl">
                                <i data-lucide="qr-code" class="w-6 h-6 text-primary"></i>
                            </div>
                            <h3 class="font-extrabold text-2xl text-gray-900 tracking-tight">Escanear QR Code</h3>
                        </div>
                        <p class="text-sm font-medium text-gray-500 ml-1">
                            Requisito: <span class="text-primary font-bold">{{ $requirementId ? Requirement::find($this->requirementId)?->title : 'Carregando...' }}</span>
                        </p>
                    </div>

                    <div class="flex-1 relative p-4 md:p-8 pt-0 flex flex-col justify-center items-center">
                        <div id="container-leitor" class="w-full aspect-square md:aspect-video bg-gray-100 rounded-3xl overflow-hidden shadow-inner relative group border-2 border-gray-50" wire:ignore>
                            <div id="reader" class="w-full h-full"></div>
                            
                            <div id="scanner-placeholder" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-50 text-gray-900 p-6 text-center transition-all duration-300">
                                <div class="w-24 h-24 mb-6 rounded-full bg-white flex items-center justify-center shadow-sm border border-gray-100">
                                    <div class="w-16 h-16 rounded-full flex items-center justify-center animate-pulse">
                                        <i data-lucide="camera" class="w-8 h-8 text-primary"></i>
                                    </div>
                                </div>
                                <h4 class="text-2xl font-black mb-2 tracking-tight text-gray-800">Câmera Desligada</h4>
                                <p class="text-gray-500 text-sm max-w-xs mb-8 font-medium">Clique no botão abaixo para autorizar e iniciar a leitura dos QR Codes.</p>
                                
                                <button id="btn-iniciar-scan" class="group flex items-center gap-3 bg-primary text-white hover:bg-primary/90 px-10 py-4 rounded-2xl font-bold transition-all duration-300 shadow-lg shadow-primary/20 active:scale-95">
                                    <i data-lucide="play" class="w-5 h-5 fill-current transition-transform group-hover:scale-110"></i>
                                    <span>Ativar Câmera</span>
                                </button>
                            </div>
                        </div>

                        <div class="mt-6 w-full max-w-md">
                            <label for="qr" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Entrada Manual</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="keyboard" class="w-5 h-5 text-gray-400 group-focus-within:text-primary transition-colors"></i>
                                </div>
                                <input 
                                    class="w-full h-14 bg-white border-2 border-gray-100 focus:border-primary focus:ring-0 rounded-2xl pl-12 pr-4 text-lg font-bold text-gray-700 transition-all shadow-sm placeholder:text-gray-300" 
                                    type="number" 
                                    id="qr"
                                    oninput="handleInput(this.value)" 
                                    placeholder="Digite o ID do desbravador..."
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-2/5 flex flex-col bg-white">
                    <div class="p-6 md:p-8 flex items-center justify-between border-b border-gray-50">
                        <div class="flex flex-col">
                            <h4 class="font-bold text-gray-900">Desbravadores</h4>
                            <p class="text-xs text-gray-500 font-medium">{{ count($pathfinderScans) }} {{ count($pathfinderScans) == 1 ? 'adicionado' : 'adicionados' }}</p>
                        </div>
                        <div class="flex gap-2">
                             <button wire:click="closeModal" class="p-3 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-all">
                                <i data-lucide="minimize-2" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-slate-50/50">
                        @if (empty($pathfinderScans))
                            <div class="h-full flex flex-col items-center justify-center text-center p-8 opacity-40">
                                <div class="p-4 bg-gray-200 rounded-full mb-4">
                                    <i data-lucide="users" class="w-8 h-8 text-gray-500"></i>
                                </div>
                                <p class="text-sm font-bold text-gray-500">Nenhum scan realizado ainda</p>
                                <p class="text-xs text-gray-400">Os desbravadores aparecerão aqui conforme você escaneia</p>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach ($pathfinderScans as $pathfinderScan)
                                    <div class="flex items-center justify-between p-4 bg-white rounded-2xl border border-gray-100 shadow-sm group hover:border-primary transition-all duration-300">
                                        <div class="flex items-center gap-4">
                                            <div class="relative">
                                                <div class="flex items-center justify-center w-12 h-12 bg-primary/10 text-primary rounded-xl font-black text-lg border border-primary/5">
                                                    {{ strtoupper(substr($pathfinderScan->name, 0, 1)) }}{{ strtoupper(substr($pathfinderScan->name, strpos($pathfinderScan->name, ' ') ? strpos($pathfinderScan->name, ' ') + 1 : 1, 1)) }}
                                                </div>
                                                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full flex items-center justify-center shadow-sm">
                                                    <i data-lucide="check" class="w-3 h-3 text-white"></i>
                                                </div>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-bold text-gray-800 leading-tight">{{ $pathfinderScan->name }}</span>
                                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">ID: #{{ $pathfinderScan->id }}</span>
                                            </div>
                                        </div>
                                        <button wire:click="removePathfinderScan({{ $pathfinderScan->id }})" class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="p-6 md:p-8 bg-white border-t border-gray-100">
                        <div class="grid grid-cols-2 gap-4">
                            <button wire:click="closeModal" 
                                class="flex items-center justify-center gap-2 px-6 py-4 bg-gray-50 text-gray-500 hover:bg-gray-100 font-bold rounded-2xl transition-all active:scale-95">
                                <span>Cancelar</span>
                            </button>
                            <button wire:click="save" 
                                class="flex items-center justify-center gap-2 px-6 py-4 bg-primary text-white hover:bg-primary/90 font-bold rounded-2xl transition-all shadow-lg shadow-primary/30 active:scale-95">
                                <i data-lucide="cloud-upload" class="w-5 h-5"></i>
                                <span>Finalizar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    function handleInput(pathfinderId) {
        if (!pathfinderId) return;
        Livewire.dispatch('scan-qrcode', { pathfinderId: pathfinderId });
        const input = document.getElementById('qr');
        if (input) {
            input.value = '';
            input.focus();
        }
    }

    window.html5QrcodeScanner = null;
    window.qrLastScan = null;
    window.qrLastScanTime = 0;

    async function stopScanner() {
        if (window.html5QrcodeScanner && window.html5QrcodeScanner.isScanning) {
            try {
                await window.html5QrcodeScanner.stop();
                window.html5QrcodeScanner = null; // Nullify the scanner instance
                const placeholder = document.getElementById("scanner-placeholder");
                if (placeholder) placeholder.classList.remove("hidden"); // Show placeholder
                console.log("Scanner parado.");
            } catch (err) {
                console.warn("Erro ao parar scanner:", err);
            }
        }
    }

    async function startScanner() {
        const readerElement = document.getElementById("reader");
        const placeholder = document.getElementById("scanner-placeholder");

        if (!readerElement) return;
        
        await stopScanner();

        if (!window.html5QrcodeScanner) {
            window.html5QrcodeScanner = new Html5Qrcode("reader");
        }

        const config = {
            fps: 15,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

        const onSuccess = async (decodedText) => {
            const now = Date.now();
            
            if (decodedText === window.qrLastScan && (now - window.qrLastScanTime) < 3000) {
                return;
            }

            window.qrLastScan = decodedText;
            window.qrLastScanTime = now;

            console.log("QR Lido:", decodedText);
            handleInput(decodedText);            
        };

        window.html5QrcodeScanner.start({ facingMode: "environment" }, config, onSuccess)
            .then(() => {
                if (placeholder) placeholder.classList.add("hidden");
            })
            .catch(err => {
                console.warn("Câmera traseira falhou, tentando frontal...");
                window.html5QrcodeScanner.start({ facingMode: "user" }, config, onSuccess)
                    .then(() => {
                        if (placeholder) placeholder.classList.add("hidden");
                    })
                    .catch(err2 => {
                        console.error("Erro total na câmera:", err2);
                        alert("Não foi possível acessar a câmera. Verifique as permissões.");
                    });
            });
    }

    function attachScannerEvent() {
        const btn = document.getElementById('btn-iniciar-scan');
        if (btn) {
            btn.replaceWith(btn.cloneNode(true));
            const newBtn = document.getElementById('btn-iniciar-scan');
            newBtn.addEventListener('click', startScanner);
        }
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    document.addEventListener('livewire:init', () => {
        Livewire.on('open-modal-scan-qrcode', () => {
            setTimeout(attachScannerEvent, 150);
        });

        Livewire.on('stop-qr-scanner', () => {
            stopScanner();
        });
    });

    document.addEventListener('livewire:navigated', () => {
        setTimeout(attachScannerEvent, 150);
    });
</script>
