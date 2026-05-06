<?php

namespace App\Services;

use App\Models\Visit;
use App\Repositories\CityRepository;
use App\Repositories\VisitRepository;
use Illuminate\Support\Facades\DB;

class StoreVisitService
{
    public function __construct(
        private readonly CityRepository $cityRepository,
        private readonly VisitRepository $visitRepository,
    ) {
    }

    public function handle(string $ip, string $city, string $device): Visit
    {
        return DB::transaction(function () use ($ip, $city, $device): Visit {
            $cityModel = $this->cityRepository->firstOrCreateByName($city);
            $visit = $this->visitRepository->firstOrCreateByIpAndDevice($ip, $device, $cityModel->id);

            if ($visit->wasRecentlyCreated) {
                $this->cityRepository->incrementVisitsCount($cityModel);
            } else {
                $this->visitRepository->incrementVisitsCount($visit);
                $visit->refresh();
            }

            return $visit;
        });
    }
}
