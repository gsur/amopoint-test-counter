<?php

namespace App\Http\Controllers;

use App\Services\VisitAnalyticsService;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(VisitAnalyticsService $visitAnalyticsService): View
    {
        return view('welcome', [
            'hourlyVisitsChart' => $visitAnalyticsService->getHourlyUniqueVisitsForLastDay(),
            'cityShareChart' => $visitAnalyticsService->getCityShareForLastDay(),
        ]);
    }
}
