<?php

use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Services\RequirementService;

new #[Layout('livewire.layouts.app')] class extends Component {
    public string $title = 'Pontuação';
    public string $description = 'Gerencie as pontuações dos desbravadores';
    public string $icon = 'smartphone-charging';

    public ?Collection $requirements = null;

    public function mount(RequirementService $service): void
    {
        $this->requirements = $service->getRequirements('pathfinder');
    }
};

?>

<div>
    <livewire:components.base.header-page :title="$title" :description="$description" :icon="$icon" />

    <div class="p-8 pb-0">
        <livewire:components.points.scan-qrcode />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-8">
        @foreach ($requirements as $requirement)
            <livewire:components.points.card :requirement="$requirement" />
        @endforeach
    </div>

</div>
