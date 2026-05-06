<?php

namespace App\Repositories;

use App\Models\City;

class CityRepository
{
    public function firstOrCreateByName(string $cityName): City
    {
        return City::query()->firstOrCreate(
            ['city' => $cityName],
        );
    }

    public function incrementVisitsCount(City $city): void
    {
        $city->increment('visits_count');
    }
}
