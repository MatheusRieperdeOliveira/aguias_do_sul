<?php

namespace App\Exports;

use App\Models\Point;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PointExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {        
        return Point::query()->select('requirements.title as requirement_title', 'requirements.score as requirement_score', 'pathfinders.name as pathfinder_name', 'units.name as unit_name', 'points.created_at as when_linked')->join('requirements', 'requirement_id', '=', 'requirements.id')->join('pathfinders', 'pathfinder_id', '=', 'pathfinders.id')->join('units', 'pathfinders.unit_id', '=', 'units.id')
        ->orderBy('when_linked', 'desc');
    }

    public function headings(): array
    {
        return [
            'Requisito',
            'Pontuação',
            'Desbravador',
            'Unidade',
            'Quando_vinculado',
        ];
    }

    public function map($point): array
    {
        return [
            $point->requirement_title ?? 'N/A',
            $point->requirement_score ?? 'N/A',
            $point->pathfinder_name ?? 'N/A',
            $point->unit_name ?? 'N/A',
            $point->when_linked ? Carbon::parse($point->when_linked)->format('d/m/Y H:i:s') : 'N/A',
        ];
    }
}
