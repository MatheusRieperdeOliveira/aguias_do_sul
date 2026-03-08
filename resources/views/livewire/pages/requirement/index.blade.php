<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('livewire.layouts.app')]
class extends Component
{
    public string $title = 'Requisitos';
    public string $description = 'Gerencie os requisitos do clube';
    public string $icon = 'chart-no-axes-column';
    public string $event = 'open-requirement-modal';
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
        <livewire:components.requirement.form/>
        <livewire:components.requirement.table/>
    </div>
</div>
