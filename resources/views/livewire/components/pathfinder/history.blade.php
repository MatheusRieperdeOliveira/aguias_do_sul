<?php 

use Livewire\Component;
use App\Models\Pathfinder;

new class extends Component {
    public ?int $pathfinderId = null;

    #[Computed]
    public function pathfinder()
    {
        return Pathfinder::query()->with(['points', 'points.requirement'])->find($this->pathfinderId);
    }
};

?>

<div class="overflow-x-auto rounded-lg border border-gray-200">
    @if ($this->pathfinder())
        <table class="w-full border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase tracking-wider">Conquista / Requisito</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pontos</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Data e Hora</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($this->pathfinder()->points->sortByDesc('created_at') as $point)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">{{ $point->requirement->title }}</span>
                                <span class="text-xs text-gray-500">{{ $point->created_at->diffForHumans() }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 w-16">
                                +{{ $point->requirement->score }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-900 font-medium">{{ $point->created_at->translatedFormat('d/m/Y') }}</span>
                                <span class="text-xs text-gray-500">{{ $point->created_at->format('H:i') }}</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-500">Nenhum registro encontrado</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif
</div>
