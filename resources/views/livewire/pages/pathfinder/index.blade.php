<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Services\PathfinderService;

new #[Layout('livewire.layouts.app')]
class extends Component {
    public string $title = 'Desbravadores';
    public string $description = 'Gerencie os membros do clube';
    public string $icon = 'flame-kindling';
    public string $event = 'open-pathfinder-modal';

};
?>

<div>
    <livewire:components.base.header-page
        :title="$title"
        :description="$description"
        :icon="$icon"
        :event="$event"
    />

    <div class="flex flex-col items-center justify-between w-full p-8 gap-4">
        <livewire:components.pathfinder.form/>
        <livewire:components.pathfinder.barcode/>
        <livewire:components.pathfinder.table/>
    </div>
</div>
