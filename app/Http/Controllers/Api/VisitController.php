<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVisitRequest;
use App\Services\StoreVisitService;
use Illuminate\Http\JsonResponse;

class VisitController extends Controller
{
    public function __construct(
        private readonly StoreVisitService $storeVisitService,
    ) {
    }

    public function store(StoreVisitRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $visit = $this->storeVisitService->handle(

            $validated[ 'ip' ],
            $validated[ 'city' ],
            $validated[ 'device' ],
        );

        return response()->json( [
            
            'id' => $visit->id,
            'message' => 'Visit stored successfully.',
        ] );
    }
}
