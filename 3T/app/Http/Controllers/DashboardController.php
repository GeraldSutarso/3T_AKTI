<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Period;
use App\Models\Generation;
use App\Models\Kpi;
use App\Models\Physical;
use App\Models\BodyData;
use App\Models\MinimumValue;

class DashboardController extends Controller
{
    public function dashboardData()
    {
        // Fetch the current period and generation data (adjust the queries as needed)
        $currentPeriod = Period::where('is_current', true)->first();
        $currentGeneration = Generation::latest()->first(); // Adjust as needed

        // Get minimum threshold values for comparison
        $minimumValues = MinimumValue::first();

        // Example: count KPI documents below a threshold
        $kpiUnderMinCount = Kpi::where('kedisiplinan_point', '<', $minimumValues->kedisiplinan_min)->count();

        // Prepare aggregated stats (expand as needed)
        $stats = [
            'KPI Under Minimum' => $kpiUnderMinCount,
            'Physical Under Minimum' => Physical::where('bleep', '<', $minimumValues->bleep_kkm)->count(),
            // ... add more stats if needed
        ];

        // Prepare dummy chart data for demonstration (structure as needed)
        $chartData = [
            'kpi'       => ['labels' => ['Below', 'Above'], 'values' => [5, 45]],
            'physical'  => ['labels' => ['Below', 'Above'], 'values' => [3, 47]],
            'body_data' => ['labels' => ['Below', 'Above'], 'values' => [2, 48]],
        ];

        // Get mini lists (e.g., latest 5 records) of current documents
        $currentKpi = Kpi::latest()->limit(5)->get();
        $currentPhysical = Physical::latest()->limit(5)->get();
        $currentBodyData = BodyData::latest()->limit(5)->get();

        return response()->json([
            'currentGeneration' => $currentGeneration,
            'currentPeriod'     => $currentPeriod,
            'stats'             => $stats,
            'chartData'         => $chartData,
            'currentKpi'        => $currentKpi,
            'currentPhysical'   => $currentPhysical,
            'currentBodyData'   => $currentBodyData,
        ]);
    }
    // Example inside your Dashboard.vue component
async fetchDashboardData() {
    try {
      const response = await axios.get('/dashboard-data');
      this.currentGeneration = response.data.currentGeneration;
      this.currentPeriod = response.data.currentPeriod;
      this.stats = response.data.stats;
      this.chartData = response.data.chartData;
      this.currentKpi = response.data.currentKpi;
      this.currentPhysical = response.data.currentPhysical;
      this.currentBodyData = response.data.currentBodyData;
    } catch (error) {
      console.error('Error fetching dashboard data:', error);
    }
  }
  
}
