<?php

namespace App\Services;

use App\Models\Pathfinder;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PathfinderService
{
    public function storePathfinder(array $pathfinder)
    {
        $dataPhone = $this->formatedPhone($pathfinder['full_phone']);

        return Pathfinder::updateOrCreate(
            [
                'name' => $pathfinder['name'],
                'status' => 'active',
                'ddd' => $dataPhone['ddd'],
                'phone' => $dataPhone['phone'],
                'full_phone' => $pathfinder['full_phone'],
                'birthday' => $pathfinder['birthday'],
                'age' => $this->formatedAge($pathfinder['birthday']),
                'responsible_name' => null,
                'responsible_phone' => null,
                'email' => $pathfinder['email'],
                'address' => $pathfinder['address'],
            ]
        );
    }

    private function formatedPhone(string $phone): array
    {
        $phone = str_replace(['(', ')', '-', ' '], '', $phone);

        return [
            'ddd' => substr($phone, 0, 2),
            'phone' => substr($phone, 2),
        ];
    }

    private function formatedAge(string $birthday): int
    {
        return Carbon::parse($birthday)->age;
    }

    public function getPathfinders(): ?Collection
    {
        return Pathfinder::all();
    }
}
