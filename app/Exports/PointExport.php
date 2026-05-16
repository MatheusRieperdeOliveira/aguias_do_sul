<?php

namespace App\Exports;

use App\Models\Point;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PointExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Point::query()
            ->select([
                'requirements.title as requirement_title',
                'requirements.score as requirement_score',
                'pathfinders.name as pathfinder_name',
                'units.name as unit_name',
                'points.created_at as when_linked',
                'recorder.name as recorded_by_name',
                'recorder.email as recorded_by_email',
                'points.recorded_from_ip as recorded_from_ip',
            ])
            ->join('requirements', 'points.requirement_id', '=', 'requirements.id')
            ->join('pathfinders', 'points.pathfinder_id', '=', 'pathfinders.id')
            ->join('units', 'pathfinders.unit_id', '=', 'units.id')
            ->leftJoin('users as recorder', 'points.recorded_by_user_id', '=', 'recorder.id')
            ->orderByDesc('when_linked');
    }

    public function headings(): array
    {
        return [
            'Requisito',
            'Pontuação',
            'Desbravador',
            'Unidade',
            'Quando_vinculado',
            'Registrado_por',
            'IP_origem',
        ];
    }

    public function map($point): array
    {
        $recordedBy = $point->recorded_by_name
            ? trim($point->recorded_by_name.($point->recorded_by_email ? ' ('.$point->recorded_by_email.')' : ''))
            : 'N/A';

        return [
            $point->requirement_title ?? 'N/A',
            $point->requirement_score ?? 'N/A',
            $point->pathfinder_name ?? 'N/A',
            $point->unit_name ?? 'N/A',
            $point->when_linked ? Carbon::parse($point->when_linked)->format('d/m/Y H:i:s') : 'N/A',
            $recordedBy,
            $point->recorded_from_ip ?? 'N/A',
        ];
    }
}
