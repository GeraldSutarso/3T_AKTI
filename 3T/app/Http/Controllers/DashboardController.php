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

    public function getChartData()
{
    // Get periods sorted correctly
    $periods = Period::orderBy('semester')
        ->orderBy('month')
        ->get(['id', 'month', 'semester', 'is_current']);

    $formattedPeriods = $periods->map(function ($period) {
        return [
            'id' => $period->id,
            'name' => "Semester {$period->semester} - {$period->month}",
            'is_current' => $period->is_current
        ];
    });

    return response()->json([
        'bodyData' => $this->getBodyDataStats($formattedPeriods),
        'physical' => $this->getPhysicalStats($formattedPeriods),
        'kpi' => $this->getKpiStats($formattedPeriods)
    ]);
}

private function getKpiStats($periods)
{
    $categories = ['kedisiplinan_nilai', 'kesehatan_nilai', 'safety_nilai', 'r5_nilai', 'vt7_nilai'];
    $kpiData = [];

    foreach ($categories as $category) {
        $pieData = [
            'A' => Kpi::where($category, '>=', 80)->count(),
            'B' => Kpi::whereBetween($category, [60, 79])->count(),
            'C' => Kpi::whereBetween($category, [40, 59])->count(),
            'D' => Kpi::where($category, '<', 40)->count(),
        ];

        $barFinal = [];
        $barCurrent = [];

        foreach ($periods as $period) {
            $barFinal[$period['name']] = [
                'A' => FinalKpi::where('period_id', $period['id'])->where($category, '>=', 80)->count(),
                'B' => FinalKpi::where('period_id', $period['id'])->whereBetween($category, [60, 79])->count(),
                'C' => FinalKpi::where('period_id', $period['id'])->whereBetween($category, [40, 59])->count(),
                'D' => FinalKpi::where('period_id', $period['id'])->where($category, '<', 40)->count(),
            ];

            $barCurrent[$period['name']] = [
                'A' => Kpi::where('period_id', $period['id'])->where($category, '>=', 80)->count(),
                'B' => Kpi::where('period_id', $period['id'])->whereBetween($category, [60, 79])->count(),
                'C' => Kpi::where('period_id', $period['id'])->whereBetween($category, [40, 59])->count(),
                'D' => Kpi::where('period_id', $period['id'])->where($category, '<', 40)->count(),
            ];
        }

        $kpiData[$category] = [
            'pie' => $pieData,
            'barFinal' => $barFinal,
            'barCurrent' => $barCurrent
        ];
    }

    return $kpiData;
}

private function getBodyDataStats($periods)
{
    $under = [];
    $inBetween = [];
    $over = [];

    foreach ($periods as $period) {
        $finalUnder = FinalBodyData::where('period_id', $period['id'])
            ->whereColumn('conclusion', '<', 'bb_under')
            ->count();

        $currentUnder = BodyData::where('period_id', $period['id'])
            ->whereColumn('conclusion', '<', 'bb_under')
            ->count();

        $under[$period['name']] = $finalUnder + $currentUnder;

        $finalInBetween = FinalBodyData::where('period_id', $period['id'])
            ->whereColumn('conclusion', '>=', 'bb_under')
            ->whereColumn('conclusion', '<=', 'bb_over')
            ->count();

        $currentInBetween = BodyData::where('period_id', $period['id'])
            ->whereColumn('conclusion', '>=', 'bb_under')
            ->whereColumn('conclusion', '<=', 'bb_over')
            ->count();

        $inBetween[$period['name']] = $finalInBetween + $currentInBetween;

        $finalOver = FinalBodyData::where('period_id', $period['id'])
            ->whereColumn('conclusion', '>', 'bb_over')
            ->count();

        $currentOver = BodyData::where('period_id', $period['id'])
            ->whereColumn('conclusion', '>', 'bb_over')
            ->count();

        $over[$period['name']] = $finalOver + $currentOver;
    }

    return [
        'periods' => array_column($periods->toArray(), 'name'),
        'under' => $under,
        'inBetween' => $inBetween,
        'over' => $over,
        'totalUnder' => array_sum($under),
        'totalInBetween' => array_sum($inBetween),
        'totalOver' => array_sum($over)
    ];
}

private function getPhysicalStats($periods)
{
    $avgAbove = [];
    $bleepAbove = [];

    foreach ($periods as $period) {
        $finalAvg = FinalPhysical::where('period_id', $period['id'])
            ->whereColumn('avg', '>=', 'avg_kkm')
            ->count();

        $currentAvg = Physical::where('period_id', $period['id'])
            ->whereColumn('avg', '>=', 'avg_kkm')
            ->count();

        $avgAbove[$period['name']] = $finalAvg + $currentAvg;

        $finalBleep = FinalPhysical::where('period_id', $period['id'])
            ->whereColumn('bleep', '>=', 'bleep_kkm')
            ->count();

        $currentBleep = Physical::where('period_id', $period['id'])
            ->whereColumn('bleep', '>=', 'bleep_kkm')
            ->count();

        $bleepAbove[$period['name']] = $finalBleep + $currentBleep;
    }

    return [
        'periods' => array_column($periods->toArray(), 'name'),
        'avgAbove' => $avgAbove,
        'bleepAbove' => $bleepAbove,
        'currentAvgAbove' => array_sum($avgAbove),
        'currentBleepAbove' => array_sum($bleepAbove),
        'currentAvgBelow' => Physical::count() - array_sum($avgAbove),
        'currentBleepBelow' => Physical::count() - array_sum($bleepAbove)
    ];
}

}
