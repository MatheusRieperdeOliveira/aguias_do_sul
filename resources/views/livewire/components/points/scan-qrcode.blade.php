<?php

use App\Models\Pathfinder;
use Livewire\Attributes\On;
use Livewire\Volt\Component;
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

        if (collect($this->pathfinderScans)->contains('id', $pathfinder->id)) {
            return;
        }

        $this->pathfinderScans[] = $pathfinder;
    }

    public function save(): void
    {
        $this->validate(
            [
                'requirementId' => ['required', 'integer', 'not_in:0', 'exists:requirements,id'],
                'pathfinderScans' => ['required', 'array', 'min:1'],
            ],
            [
                'requirementId.required' => 'Requisito inválido.',
                'requirementId.exists' => 'Requisito não encontrado.',
                'pathfinderScans.required' => 'Adicione pelo menos um desbravador.',
                'pathfinderScans.min' => 'Adicione pelo menos um desbravador.',
            ],
        );

        $now = now();
        $recordedByUserId = auth()->id();
        $recordedFromIp = (string) request()->ip();

        DB::transaction(function () use ($now, $recordedByUserId, $recordedFromIp) {
            $rows = collect($this->pathfinderScans)
                ->map(
                    fn ($pathfinder) => [
                        'requirement_id' => $this->requirementId,
                        'pathfinder_id' => $pathfinder->id,
                        'recorded_by_user_id' => $recordedByUserId,
                        'recorded_from_ip' => $recordedFromIp,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ],
                )
                ->toArray();

            DB::table('points')->insert($rows);
        });

        $this->closeModal();
    }

    public function closeModal(): void
    {
        $this->requirementId = 0;
        $this->pathfinderScans = [];
        $this->open = false;
    }

    public function removePathfinderScan($pathfinderId): void
    {
        $this->pathfinderScans = array_values(array_filter(
            $this->pathfinderScans,
            fn(Pathfinder $pathfinder) => $pathfinder->id !== $pathfinderId,
        ));
    }
}; ?>

<div>
    @if ($open)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" wire:transition.opacity>
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="closeModal"></div>

            <div
                class="relative bg-white w-full max-w-3xl max-h-[90vh] md:max-h-[85vh] shadow-2xl rounded-3xl overflow-hidden flex flex-col border border-white/20 transform transition-all"
                @click.stop
            >
                <div class="p-6 md:p-8 border-b border-gray-50 flex items-center justify-between shrink-0">
                    <div class="flex flex-col">
                        <h3 class="font-bold text-gray-900 text-lg">Registrar desbravadores</h3>
                        <p class="text-xs text-gray-500 font-medium mt-0.5">Informe o ID de cada desbravador</p>
                    </div>
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="p-3 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-all"
                    >
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                @if ($errors->has('pathfinderScans') || $errors->has('requirementId'))
                    <div class="px-6 md:px-8">
                        @error('pathfinderScans')
                            <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                        @error('requirementId')
                            <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div class="p-6 md:p-8 shrink-0">
                    <label for="qr" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">
                        ID do desbravador
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="hash" class="w-5 h-5 text-gray-400 group-focus-within:text-primary transition-colors"></i>
                        </div>
                        <input
                            class="w-full h-14 bg-white border-2 border-gray-100 focus:border-primary focus:ring-0 rounded-2xl pl-12 pr-4 text-lg font-bold text-gray-700 transition-all shadow-sm placeholder:text-gray-300"
                            type="number"
                            id="qr"
                            autocomplete="off"
                            onkeydown="if (event.key === 'Enter') { event.preventDefault(); handleScanQrModalInput(this.value); }"
                            placeholder="Digite o ID e pressione Enter"
                        >
                    </div>
                </div>

                <div class="flex-1 min-h-0 overflow-y-auto px-6 md:px-8 pb-4 bg-slate-50/50">
                    @if (empty($pathfinderScans))
                        <div class="flex flex-col items-center justify-center text-center py-12 px-4 opacity-50">
                            <div class="p-4 bg-gray-200 rounded-full mb-4">
                                <i data-lucide="users" class="w-8 h-8 text-gray-500"></i>
                            </div>
                            <p class="text-sm font-bold text-gray-500">Nenhum desbravador na lista</p>
                            <p class="text-xs text-gray-400 mt-1 max-w-xs">Use o campo acima para adicionar pelo ID</p>
                        </div>
                    @else
                        <p class="text-xs text-gray-500 font-medium mb-3">
                            {{ count($pathfinderScans) }} {{ count($pathfinderScans) === 1 ? 'adicionado' : 'adicionados' }}
                        </p>
                        <div class="space-y-3">
                            @foreach ($pathfinderScans as $pathfinderScan)
                                <div
                                    class="flex items-center justify-between p-4 bg-white rounded-2xl border border-gray-100 shadow-sm group hover:border-primary transition-all duration-300"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <div
                                                class="flex items-center justify-center w-12 h-12 bg-primary/10 text-primary rounded-xl font-black text-lg border border-primary/5"
                                            >
                                                {{ strtoupper(substr($pathfinderScan->name, 0, 1)) }}{{ strtoupper(substr($pathfinderScan->name, strpos($pathfinderScan->name, ' ') ? strpos($pathfinderScan->name, ' ') + 1 : 1, 1)) }}
                                            </div>
                                            <div
                                                class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full flex items-center justify-center shadow-sm"
                                            >
                                                <i data-lucide="check" class="w-3 h-3 text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <span class="font-bold text-gray-800 leading-tight truncate">{{ $pathfinderScan->name }}</span>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">ID #{{ $pathfinderScan->id }}</span>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        wire:click="removePathfinderScan({{ $pathfinderScan->id }})"
                                        class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all shrink-0"
                                    >
                                        <i data-lucide="trash-2" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="p-6 md:p-8 bg-white border-t border-gray-100 shrink-0">
                    <div class="grid grid-cols-2 gap-4">
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="flex items-center justify-center gap-2 px-6 py-4 bg-gray-50 text-gray-500 hover:bg-gray-100 font-bold rounded-2xl transition-all active:scale-95"
                        >
                            <span>Cancelar</span>
                        </button>
                        <button
                            type="button"
                            wire:click="save"
                            class="flex items-center justify-center gap-2 px-6 py-4 bg-primary text-white hover:bg-primary/90 font-bold rounded-2xl transition-all shadow-lg shadow-primary/30 active:scale-95"
                        >
                            <i data-lucide="cloud-upload" class="w-5 h-5"></i>
                            <span>Finalizar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function handleScanQrModalInput(pathfinderId) {
        const trimmed = String(pathfinderId).trim();

        if (!trimmed) {
            return;
        }

        Livewire.dispatch('scan-qrcode', { pathfinderId: trimmed });

        const input = document.getElementById('qr');

        if (input) {
            input.value = '';
            input.focus();
        }
    }

    function refreshScanQrModalLucide() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    document.addEventListener('livewire:init', () => {
        Livewire.on('open-modal-scan-qrcode', () => {
            setTimeout(() => {
                refreshScanQrModalLucide();
                const input = document.getElementById('qr');

                if (input) {
                    input.focus();
                }
            }, 150);
        });
    });

    document.addEventListener('livewire:navigated', () => {
        setTimeout(refreshScanQrModalLucide, 150);
    });
</script>
