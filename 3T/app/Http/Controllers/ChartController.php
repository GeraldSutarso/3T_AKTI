<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BodyData;
use App\Models\FinalBodyData;
use App\Models\Physical;
use App\Models\FinalPhysical;
use App\Models\Kpi;
use App\Models\FinalKpi;
use App\Models\User;
use App\Models\Generation;

class ChartController extends Controller
{
    /**
     * Get body data statistics for charts
     */
    public function getBodyDataCharts($generationId)
    {
        // Get the generation
        $generation = Generation::findOrFail($generationId);
        
        // Get users in this generation
        $userIds = User::where('generation_id', $generationId)->pluck('id');
        
        // Get all periods for this generation to use as labels
        $periods = BodyData::whereIn('user_id', $userIds)
            ->distinct()
            ->orderBy('period')
            ->pluck('period');
            
        // Initialize data arrays
        $underOverData = [];
        $betweenData = [];
        
        // For each period, calculate the number of users in each category
        foreach ($periods as $period) {
            // Count users with body data conclusion under minimum_values or over maximum_values
            $underOver = BodyData::whereIn('user_id', $userIds)
                ->where('period', $period)
                ->where(function($query) {
                    $query->where('conclusion', 'bb_under')
                          ->orWhere('conclusion', 'bb_over');
                })
                ->count();
                
            // Count users with body data conclusion between minimum and maximum values
            $between = BodyData::whereIn('user_id', $userIds)
                ->where('period', $period)
                ->whereNotIn('conclusion', ['bb_under', 'bb_over'])
                ->count();
                
            $underOverData[] = $underOver;
            $betweenData[] = $between;
        }
        
        // Get overall distribution for pie chart
        $pieUnder = BodyData::whereIn('user_id', $userIds)
            ->where('conclusion', 'bb_under')
            ->count();
            
        $pieOver = BodyData::whereIn('user_id', $userIds)
            ->where('conclusion', 'bb_over')
            ->count();
            
        $pieBetween = BodyData::whereIn('user_id', $userIds)
            ->whereNotIn('conclusion', ['bb_under', 'bb_over'])
            ->count();
        
        return response()->json([
            'lineChart' => [
                'labels' => $periods,
                'underOver' => $underOverData,
                'between' => $betweenData
            ],
            'pieChart' => [
                'under' => $pieUnder,
                'over' => $pieOver,
                'between' => $pieBetween
            ]
        ]);
    }
    
    /**
     * Get physical data statistics for charts
     */
    public function getPhysicalCharts($generationId)
    {
        // Get the generation
        $generation = Generation::findOrFail($generationId);
        
        // Get users in this generation
        $userIds = User::where('generation_id', $generationId)->pluck('id');
        
        // Get AVG and Bleep KKM values from settings or use defaults
        $avgKkm = config('settings.avg_kkm', 70);
        $bleepKkm = config('settings.bleep_kkm', 8);
        
        // Count users with final physical avg values above KKM
        $finalAvgAboveKkm = FinalPhysical::whereIn('user_id', $userIds)
            ->where('avg', '>=', $avgKkm)
            ->count();
            
        // Count users with current physical avg values above KKM
        $currentAvgAboveKkm = Physical::whereIn('user_id', $userIds)
            ->where('avg', '>=', $avgKkm)
            ->count();
            
        // Count users with final physical bleep values above KKM
        $finalBleepAboveKkm = FinalPhysical::whereIn('user_id', $userIds)
            ->where('bleep', '>=', $bleepKkm)
            ->count();
            
        // Count users with current physical bleep values above KKM
        $currentBleepAboveKkm = Physical::whereIn('user_id', $userIds)
            ->where('bleep', '>=', $bleepKkm)
            ->count();
            
        // Get current distribution for pie charts
        $avgBelowKkm = Physical::whereIn('user_id', $userIds)
            ->where('avg', '<', $avgKkm)
            ->count();
            
        $avgAboveKkm = Physical::whereIn('user_id', $userIds)
            ->where('avg', '>=', $avgKkm)
            ->count();
            
        $bleepBelowKkm = Physical::whereIn('user_id', $userIds)
            ->where('bleep', '<', $bleepKkm)
            ->count();
            
        $bleepAboveKkm = Physical::whereIn('user_id', $userIds)
            ->where('bleep', '>=', $bleepKkm)
            ->count();
        
        return response()->json([
            'barCharts' => [
                'finalAvgAboveKKM' => $finalAvgAboveKkm,
                'currentAvgAboveKKM' => $currentAvgAboveKkm,
                'finalBleepAboveKKM' => $finalBleepAboveKkm,
                'currentBleepAboveKKM' => $currentBleepAboveKkm
            ],
            'pieCharts' => [
                'avgBelowKKM' => $avgBelowKkm,
                'avgAboveKKM' => $avgAboveKkm,
                'bleepBelowKKM' => $bleepBelowKkm,
                'bleepAboveKKM' => $bleepAboveKkm
            ]
        ]);
    }
    
    /**
     * Get KPI data statistics for charts
     */
    public function getKpiCharts($generationId)
    {
        // Get the generation
        $generation = Generation::findOrFail($generationId);
        
        // Get users in this generation
        $userIds = User::where('generation_id', $generationId)->pluck('id');
        
        // KPI fields to analyze
        $kpiFields = [
            'kedisiplinan_nilai',
            'kesehatan_nilai',
            'safety_nilai',
            'r5_nilai',
            'vt7_nilai'
        ];
        
        // Initialize arrays for pie chart and bar chart data
        $pieChartsData = [];
        $barChartsData = [];
        
        foreach ($kpiFields as $index => $field) {
            // For pie charts - current KPI distribution
            $currentKpiA = Kpi::whereIn('user_id', $userIds)
                ->where($field, 'A')
                ->count();
                
            $currentKpiB = Kpi::whereIn('user_id', $userIds)
                ->where($field, 'B')
                ->count();
                
            $currentKpiC = Kpi::whereIn('user_id', $userIds)
                ->where($field, 'C')
                ->count();
                
            $currentKpiD = Kpi::whereIn('user_id', $userIds)
                ->where($field, 'D')
                ->count();
                
            $pieChartsData[$index] = [
                'A' => $currentKpiA,
                'B' => $currentKpiB,
                'C' => $currentKpiC,
                'D' => $currentKpiD
            ];
            
            // For bar charts - compare current vs final
            $finalKpiA = FinalKpi::whereIn('user_id', $userIds)
                ->where($field, 'A')
                ->count();
                
            $finalKpiB = FinalKpi::whereIn('user_id', $userIds)
                ->where($field, 'B')
                ->count();
                
            $finalKpiC = FinalKpi::whereIn('user_id', $userIds)
                ->where($field, 'C')
                ->count();
                
            $finalKpiD = FinalKpi::whereIn('user_id', $userIds)
                ->where($field, 'D')
                ->count();
                
            $barChartsData[$index] = [
                'current' => [
                    'A' => $currentKpiA,
                    'B' => $currentKpiB,
                    'C' => $currentKpiC,
                    'D' => $currentKpiD
                ],
                'final' => [
                    'A' => $finalKpiA,
                    'B' => $finalKpiB,
                    'C' => $finalKpiC,
                    'D' => $finalKpiD
                ]
            ];
        }
        
        return response()->json([
            'pieCharts' => $pieChartsData,
            'barCharts' => $barChartsData
        ]);
    }
}