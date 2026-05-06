<?php

namespace App\Services;

use App\Models\Visit;
use App\Repositories\VisitAnalyticsRepository;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class VisitAnalyticsService
{
    public function __construct(
        private readonly VisitAnalyticsRepository $visitAnalyticsRepository,
    ) {
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, int>}
     */
    public function getHourlyUniqueVisitsForLastDay(): array
    {
        $end = now()->toImmutable()->startOfHour();
        $start = $end->subHours(23);

        $visits = $this->visitAnalyticsRepository->getVisitsForPeriod($start, $end->endOfHour());
        $countsByHour = $visits
            ->groupBy(fn (Visit $visit): string => $visit->created_at->startOfHour()->format('Y-m-d H:00:00'))
            ->map(fn (Collection $items): int => $items->count());

        $labels = [];
        $values = [];

        foreach (CarbonPeriod::create($start, '1 hour', $end) as $hour) {
            $hourLabel = $hour->format('H:00');
            $hourKey = $hour->format('Y-m-d H:00:00');

            $labels[] = $hourLabel;
            $values[] = $countsByHour[$hourKey] ?? 0;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }

    /**
     * @return array{labels: array<int, string>, values: array<int, int>}
     */
    public function getCityShareForLastDay(): array
    {
        $cities = $this->visitAnalyticsRepository->getCitiesWithVisitsCount();

        return [
            'labels' => $cities->pluck('city')->values()->all(),
            'values' => $cities->pluck('visits_count')->map(fn (mixed $count): int => (int) $count)->values()->all(),
        ];
    }
}
