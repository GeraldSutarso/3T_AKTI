<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Period;
use App\Models\Generation;
use App\Models\Kpi;
use App\Models\Physical;
use App\Models\BodyData;
use App\Models\FinalKpi;
use App\Models\FinalPhysical;
use App\Models\FinalBodyData;
use App\Models\MinimumValue;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard page (Vue will handle UI).
     */
    public function index()
    {
        return view('dashboard'); // resources/views/dashboard.blade.php
    }

    /**
     * Provide Dashboard Data as JSON for Vue.
     */
    public function dashboardData()
    {
        // Get the latest period and generation
        $currentPeriod = Period::latest()->first();
        $currentGeneration = Generation::latest()->first();

        // Get minimum threshold values (Ensure it's not null)
        $minimumValues = MinimumValue::first();

        // Provide default values if MinimumValue is null
        $kedisiplinanMin = optional($minimumValues)->kedisiplinan_min ?? 0;
        $bleepKkm = optional($minimumValues)->bleep_kkm ?? 0;
        $heightKkm = optional($minimumValues)->height_kkm ?? 0;

        // Get KPI, Physical, and Body Data under the minimum threshold
        $stats = [
            'KPI Under Minimum'      => Kpi::where('kedisiplinan_point', '<', $kedisiplinanMin)->count(),
            'Physical Under Minimum' => Physical::where('bleep', '<', $bleepKkm)->count(),
            'BodyData Under Minimum' => BodyData::where('height', '<', $heightKkm)->count(),
        ];

        // Prepare chart data (dummy for now)
        $chartData = [
            'kpi'       => ['labels' => ['Below Min', 'Above Min'], 'values' => [5, 45]],
            'physical'  => ['labels' => ['Below Min', 'Above Min'], 'values' => [3, 47]],
            'body_data' => ['labels' => ['Below Min', 'Above Min'], 'values' => [2, 48]],
        ];

        // Fetch Final 3T Documents (from final tables)
        $finalKpis = FinalKpi::latest()->limit(10)->get();
        $finalPhysicals = FinalPhysical::latest()->limit(10)->get();
        $finalBodyData = FinalBodyData::latest()->limit(10)->get();

        // Fetch the latest KPI, Physical, and Body Data instead of filtering by `is_current`
        $currentKpi = Kpi::latest()->first();
        $currentPhysical = Physical::latest()->first();
        $currentBodyData = BodyData::latest()->first();

        return response()->json([
            'currentGeneration' => $currentGeneration,
            'currentPeriod'     => $currentPeriod,
            'stats'             => $stats,
            'chartData'         => $chartData,
            'finalKpis'         => $finalKpis,
            'finalPhysicals'    => $finalPhysicals,
            'finalBodyData'     => $finalBodyData,
            'currentKpi'        => $currentKpi,
            'currentPhysical'   => $currentPhysical,
            'currentBodyData'   => $currentBodyData,
        ]);
    }

}
