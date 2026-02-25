<?php

use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component {


    #[On('open-barcode-modal')]
    public function openModal(): void
    {
        dd("Oláaaaa");
    }
};

?>

<div>
    {{-- People find pleasure in different ways. I find it in keeping my mind clear. - Marcus Aurelius --}}
</div>
