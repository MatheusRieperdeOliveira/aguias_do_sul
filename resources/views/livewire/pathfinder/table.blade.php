    <?php

use Livewire\Volt\Component;

new class extends Component
{
    public $pathfinders = [];
};
?>

<div class="w-full overflow-x-auto rounded-lg border border-gray-200">
    <table class="min-w-full table-fixed border-collapse">
        <thead class="bg-white">
        <tr>
            <th class="min-w-[180px] px-4 py-2 text-left font-medium">Nome</th>
            <th class="min-w-[120px] px-4 py-2 text-left font-medium">Status</th>
            <th class="min-w-[180px] px-4 py-2 text-left font-medium">Telefone</th>
            <th class="min-w-[260px] px-4 py-2 text-left font-medium">Email</th>
            <th class="min-w-[80px] px-4 py-2 text-left font-medium">Idade</th>
            <th class="min-w-[140px] px-4 py-2 text-left font-medium">Aniversário</th>
            <th class="min-w-[50px] px-4 py-2 text-left font-medium">Ações</th>
        </tr>
        </thead>

        <tbody>
        @foreach($pathfinders as $pathfinder)
            <tr class="border-t border-gray-200">
                <td class="px-4 py-2">{{$pathfinder->name}}</td>
                <td class="px-4 py-2">
                    @if($pathfinder->status === 'active')
                        <div class="w-13 h-6 bg-primary rounded-md flex items-center justify-center text-white text-sm font-semibold">
                            <p>
                                Ativo
                            </p>
                        </div>
                    @else
                        <div class="w-13 h-6 bg-red-500 rounded-md flex items-center justify-center text-white text-sm font-semibold">
                            <p>
                                Inativo
                            </p>
                        </div>
                    @endif
                </td>
                <td class="px-4 py-2">{{$pathfinder->full_phone}}</td>
                <td class="px-4 py-2">{{$pathfinder->email}}</td>
                <td class="px-4 py-2">{{$pathfinder->age}}</td>
                <td class="px-4 py-2">{{$pathfinder->birthday}}</td>
                <td class="px-4 py-2">
                    <div class="w-full h-8 grid grid-cols-2 rounded-xl overflow-hidden text-white">
                        <a
                            class="cursor-pointer bg-blue-600 flex items-center justify-center">
                            <i data-lucide="pencil" class="w-4 h-4">
                            </i>
                        </a>
                        <a class="cursor-pointer bg-indigo-600 flex items-center justify-center">
                            <i data-lucide="barcode" class="w-4 h-4">
                            </i>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
