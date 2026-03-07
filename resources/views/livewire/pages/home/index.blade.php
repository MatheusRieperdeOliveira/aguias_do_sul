<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')]
class extends Component
{
    public string $title = 'Dashboard';
    public string $description = 'Visão geral do clube de desbravadores';
    public string $icon = 'home';

}; ?>

<div>
    <livewire:components.base.header-page
        :title="$title"
        :description="$description"
        :icon="$icon"
    />
</div>
