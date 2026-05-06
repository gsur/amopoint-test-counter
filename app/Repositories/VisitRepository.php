<?php

namespace App\Repositories;

use App\Models\Visit;

class VisitRepository
{
    public function firstOrCreateByIpAndDevice(string $ip, string $device, int $cityId): Visit
    {
        return Visit::query()->firstOrCreate(
            [
                'ip' => $ip,
                'device' => $device,
            ],
            [
                'city_id' => $cityId,
                'visits_count' => 1,
            ],
        );
    }

    public function incrementVisitsCount(Visit $visit): void
    {
        $visit->increment('visits_count');
    }
}
