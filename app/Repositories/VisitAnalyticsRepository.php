<?php

namespace App\Repositories;

use App\Models\City;
use App\Models\Visit;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class VisitAnalyticsRepository
{
    /**
     * @return Collection<int, Visit>
     */
    public function getVisitsForPeriod(CarbonImmutable $from, CarbonImmutable $to): Collection
    {
        return Visit::query()
            ->whereBetween('created_at', [$from, $to])
            ->get(['id', 'created_at']);
    }

    /**
     * @return Collection<int, City>
     */
    public function getCitiesWithVisitsCount(): Collection
    {
        return City::query()
            ->where('visits_count', '>', 0)
            ->orderByDesc('visits_count')
            ->get(['city', 'visits_count']);
    }
}
