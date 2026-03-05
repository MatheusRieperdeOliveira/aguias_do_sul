<?php

use Livewire\Volt\Component;

new class extends Component
{
    //
};
?>

<div id="modal"
     class="fixed inset-0 bg-black/50 flex items-center justify-center
            opacity-0 pointer-events-none transition-opacity duration-300">

    <div id="modalContent"
         class="bg-white rounded-lg p-6
                transform scale-95 transition-transform duration-300">
        <div class="mb-5">
            <h1 class="text-3xl font-bold text-foreground">Nova unidade</h1>
            <p class="text-muted-foreground mt-1">Informe os dados do novo desbravador</p>
        </div>
        <form method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <label>Nome
                    <input type="text" name="name" placeholder="Nome"
                           class="w-full border rounded p-2 mb-3">
                </label>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" id="closeModalBtn" class="cursor-pointer px-4 py-2 bg-gray-300 rounded">
                    Cancelar
                </button>

                <button type="submit" class="cursor-pointer px-4 py-2 bg-primary text-white rounded">
                    Salvar
                </button>
        </form>
    </div>
</div>
<script>
    const modal = document.getElementById('modal');
    const content = document.getElementById('modalContent');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');

    openBtn.addEventListener('click', () => {
        modal.classList.remove('opacity-0', 'pointer-events-none');
        content.classList.remove('scale-95');
    });

    closeBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', (e) => {
        if (e.target.id === 'modal') closeModal();
    });

    function closeModal() {
        content.classList.add('scale-95');
        modal.classList.add('opacity-0', 'pointer-events-none');
    }

</script>
