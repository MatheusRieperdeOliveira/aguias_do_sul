<?php

use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Services\RequirementService;

new #[Layout('layouts.app')]
class extends Component {
    public ?Collection $requirements = null;

    public function mount(RequirementService $service): void
    {
        $this->requirements = $service->getRequirements('pathfinder');
    }
}

?>

<div>
    <div class="flex items-center justify-between w-full p-8">
        <div>
            <h1 class="text-3xl font-bold text-foreground">Pontuação</h1>
            <p class="text-muted-foreground mt-1">Gerencie as pontuações dos desbravadores</p>
        </div>
    </div>
    <div class="grid grid-cols-4 w-full p-8 gap-4">
        @foreach($requirements as $requirement)
            <livewire:components.base.card :requirement="$requirement"/>
        @endforeach
    </div>
    <livewire:components.base.scan-qrcode/>
</div>

