<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Requirement;

new #[Layout('layouts.app')]
class extends Component {
    public Requirement $requirement;
}

?>

<div class="flex items-center w-full p-8 bg-primary text-primary-foreground rounded-lg">
    {{$requirement}}
</div>

