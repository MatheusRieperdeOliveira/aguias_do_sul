<?php

namespace App\Exports;

use App\Models\Point;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PointExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Point::with(['pathfinder', 'requirement'])->get();
    }

    public function headings(): array
    {
        return [
            'Desbravador',
            'Pontuação (Requisito)',
            'Criado em',
        ];
    }

    public function map($point): array
    {
        return [
            $point->pathfinder->name ?? 'N/A',
            $point->requirement->title ?? 'N/A',
            $point->created_at ? $point->created_at->format('d/m/Y H:i:s') : 'N/A',
        ];
    }
}
