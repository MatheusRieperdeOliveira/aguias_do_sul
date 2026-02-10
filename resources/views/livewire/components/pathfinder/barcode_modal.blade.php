<?php

use Livewire\Component;

new class extends Component
{
    public $open = false;
    public $pathfinder;

    public function mount($pathfinder)
    {
        $this->pathfinder = $pathfinder;
    }
};
?>

<div
    x-data="{ open: @entangle('open') }"
    x-on:keydown.escape.window="open = false"
            x-init="$watch('open', value => { if(value) { lucide.createIcons() } })"
>
    <div class="w-full h-8 rounded-r-lg group relative flex items-center justify-center bg-violet-600">
        <button
            @click="open = true"
            class="cursor-pointer flex items-center justify-center w-full h-full">
            <i data-lucide="qr-code" class="w-4 h-4"></i>
        </button>
        <div class="absolute bottom-full mb-2 hidden group-hover:block w-max px-2 py-1 bg-gray-800 text-white text-xs rounded shadow-lg z-50">
            Barcode
        </div>
    </div>


    <template x-if="open">
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div
                class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                @click="open = false"
            ></div>

            <div
                class="relative bg-white w-full max-w-md rounded-2xl shadow-xl p-6 z-10 animate-in fade-in zoom-in-95"
            >
                <div class="flex flex-reverce items-center justify-end mb-4">
                    <button @click="open = false" class="text-red-500">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>

                <div class="flex items-center justify-center">
{{--                    {!! DNS2D::getBarcodeSVG($pathfinder->id, 'QRCODE') !!}--}}
                </div>
            </div>
        </div>
    </template>
</div>
