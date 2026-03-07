<?php

use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Services\RequirementService;

new #[Layout('layouts.app')]
class extends Component {
    public string $title = 'Pontuação';
    public string $description = 'Gerencie as pontuações dos desbravadores';
    public string $icon = 'smartphone-charging';

    public ?Collection $requirements = null;

    public function mount(RequirementService $service): void
    {
        $this->requirements = $service->getRequirements('pathfinder');
    }
}

?>

<div>
    <livewire:components.base.header-page
        :title="$title"
        :description="$description"
        :icon="$icon"
    />

    <div class="grid grid-cols-5 w-full p-8 gap-4">
        @foreach($requirements as $requirement)
            <livewire:components.points.card :requirement="$requirement"/>
        @endforeach
    </div>
    <script src="https://unpkg.com/html5-qrcode"></script>


    <livewire:components.points.scan-qrcode/>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>


<script>

    const scanner = new Html5Qrcode("reader");

    scanner.start(
        { facingMode: "environment" },
        { fps: 60, qrbox: 250 },
        (decodedText) => {

            fetch('/qr/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: decodedText
                })
            })

        }
    )

</script>
