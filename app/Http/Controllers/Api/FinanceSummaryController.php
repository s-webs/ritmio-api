<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Finance\FinanceAnalysisService;
use Illuminate\Http\JsonResponse;

class FinanceSummaryController extends Controller
{
    public function __construct(private readonly FinanceAnalysisService $analysisService)
    {
    }

    public function weekly(): JsonResponse
    {
        return response()->json($this->analysisService->weeklySummary(auth()->user()));
    }

    public function monthly(): JsonResponse
    {
        return response()->json($this->analysisService->monthlySummary(auth()->user()));
    }
}
