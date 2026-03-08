<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('livewire.layouts.app')]
class extends Component {
    public string $title = 'Unidades';
    public string $description = 'Gerencie os unidades do clube';
    public string $icon = 'flag';
    public string $event = 'open-unit-modal';
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
        <livewire:components.unit.form />
        <livewire:components.unit.table />
    </div>
</div>
