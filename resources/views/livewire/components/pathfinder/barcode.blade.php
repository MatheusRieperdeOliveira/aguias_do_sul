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

    public function print()
    {
        $this->dispatch('print-barcode', [
            'name' => $this->pathfinder->name,
            'barcode' => DNS2D::getBarcodeSVG("$this->pathfinder->id", 'QRCODE', 5, 5)
        ]);
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
                <div class="flex items-center justify-center w-full">
                    {!! DNS2D::getBarcodeSVG("$pathfinder->id", 'QRCODE', 14, 14) !!}
                </div>
                <div class="mt-8 flex items-center justify-end gap-3">
                    <button wire:click="closeModal" class="cursor-pointer flex-1 sm:flex-none px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">
                        Cancelar
                    </button>
                    <button wire:click="print" class="cursor-pointer flex-1 sm:flex-none px-5 py-2.5 text-sm font-semibold text-white bg-primary rounded-xl transition-colors">
                        Imprimir
                    </button>
                </div>
            </div>
        </div>
    @endif

    @script
    <script>
        $wire.on('print-barcode', (data) => {
            const printWindow = window.open('', '_blank');
            const { name, barcode } = data[0];
            
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>Imprimir Código - ${name}</title>
                        <style>
                            body { 
                                display: flex; 
                                flex-direction: column; 
                                align-items: center; 
                                justify-content: center; 
                                min-height: 100vh; 
                                margin: 0; 
                                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                                color: #1e293b;
                                background: white;
                            }
                            .container {
                                text-align: center;
                                padding: 2rem;
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                            }
                            .name { 
                                font-size: 24px; 
                                font-weight: 800; 
                                margin-bottom: 2rem;
                                color: #0f172a;
                                text-transform: uppercase;
                                letter-spacing: 0.1em;
                            }
                            .barcode-wrapper { 
                                padding: 1.5rem;
                                background: white;
                                border: 1px solid #f1f5f9;
                                border-radius: 1rem;
                                box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
                            }
                            svg {
                                display: block;
                                width: 300px;
                                height: 300px;
                            }
                            @media print {
                                body { min-height: auto; }
                                .barcode-wrapper { box-shadow: none; border: none; }
                                .no-print { display: none; }
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="name">${name}</div>
                            <div class="barcode-wrapper">
                                ${barcode}
                            </div>
                        </div>
                        <script>
                            window.onload = () => {
                                setTimeout(() => {
                                    window.print();
                                    window.close();
                                }, 250);
                            };
                        <\/script>
                    </body>
                </html>
            `);
            printWindow.document.close();
        });
    </script>
    @endscript
</div>
