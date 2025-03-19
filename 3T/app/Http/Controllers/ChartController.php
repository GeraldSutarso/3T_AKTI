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
    public function getBodyDataCharts(Request $request)
    {
        $generationId = $request->query('generation_id');
        $generation = Generation::findOrFail($generationId);
        
        $userIds = User::where('generation_id', $generationId)->pluck('id');
        
        $periods = BodyData::whereIn('user_id', $userIds)
            ->distinct()
            ->orderBy('period')
            ->pluck('period');
        
        // Line Chart Data
        $lineDatasets = [
            [
                'label' => 'Under/Over Limits',
                'data' => [],
                'borderColor' => '#ff0000',
                'fill' => false
            ],
            [
                'label' => 'Within Limits',
                'data' => [],
                'borderColor' => '#4CAF50',
                'fill' => false
            ]
        ];

        foreach ($periods as $period) {
            $underOver = BodyData::whereIn('user_id', $userIds)
                ->where('period', $period)
                ->whereIn('conclusion', ['bb_under', 'bb_over'])
                ->count();
            
            $between = BodyData::whereIn('user_id', $userIds)
                ->where('period', $period)
                ->whereNotIn('conclusion', ['bb_under', 'bb_over'])
                ->count();
            
            $lineDatasets[0]['data'][] = $underOver;
            $lineDatasets[1]['data'][] = $between;
        }

        // Pie Chart Data
        $pieData = [
            'labels' => ['Under', 'Over', 'Within'],
            'datasets' => [
                [
                    'data' => [
                        BodyData::whereIn('user_id', $userIds)->where('conclusion', 'bb_under')->count(),
                        BodyData::whereIn('user_id', $userIds)->where('conclusion', 'bb_over')->count(),
                        BodyData::whereIn('user_id', $userIds)->whereNotIn('conclusion', ['bb_under', 'bb_over'])->count()
                    ],
                    'backgroundColor' => ['#ff0000', '#FFEB3B', '#4CAF50']
                ]
            ]
        ];

        return response()->json([
            'lineDataset' => [
                'labels' => $periods,
                'datasets' => $lineDatasets
            ],
            'pieDataset' => $pieData
        ]);
    }

    /**
     * Get physical data statistics for charts
     */
    public function getPhysicalCharts(Request $request)
    {
        $generationId = $request->query('generation_id');
        $generation = Generation::findOrFail($generationId);
        $userIds = User::where('generation_id', $generationId)->pluck('id');
        
        $avgKkm = config('settings.avg_kkm', 70);
        $bleepKkm = config('settings.bleep_kkm', 8);

        // Bar Charts Data
        $barCharts = [
            [
                'labels' => ['Final', 'Current'],
                'datasets' => [
                    [
                        'label' => 'Average ≥ KKM',
                        'data' => [
                            FinalPhysical::whereIn('user_id', $userIds)->where('avg', '>=', $avgKkm)->count(),
                            Physical::whereIn('user_id', $userIds)->where('avg', '>=', $avgKkm)->count()
                        ],
                        'backgroundColor' => ['#4CAF50', '#4CAF50']
                    ]
                ]
            ],
            [
                'labels' => ['Final', 'Current'],
                'datasets' => [
                    [
                        'label' => 'Bleep ≥ KKM',
                        'data' => [
                            FinalPhysical::whereIn('user_id', $userIds)->where('bleep', '>=', $bleepKkm)->count(),
                            Physical::whereIn('user_id', $userIds)->where('bleep', '>=', $bleepKkm)->count()
                        ],
                        'backgroundColor' => ['#2196F3', '#2196F3']
                    ]
                ]
            ]
        ];

        // Pie Charts Data
        $pieCharts = [
            [
                'labels' => ['≥ KKM', '< KKM'],
                'datasets' => [
                    [
                        'data' => [
                            Physical::whereIn('user_id', $userIds)->where('avg', '>=', $avgKkm)->count(),
                            Physical::whereIn('user_id', $userIds)->where('avg', '<', $avgKkm)->count()
                        ],
                        'backgroundColor' => ['#4CAF50', '#ff0000']
                    ]
                ]
            ],
            [
                'labels' => ['≥ KKM', '< KKM'],
                'datasets' => [
                    [
                        'data' => [
                            Physical::whereIn('user_id', $userIds)->where('bleep', '>=', $bleepKkm)->count(),
                            Physical::whereIn('user_id', $userIds)->where('bleep', '<', $bleepKkm)->count()
                        ],
                        'backgroundColor' => ['#2196F3', '#ff0000']
                    ]
                ]
            ]
        ];

        return response()->json([
            'barCharts' => $barCharts,
            'pieCharts' => $pieCharts
        ]);
    }

    /**
     * Get KPI data statistics for charts
     */
    public function getKpiCharts(Request $request)
    {
        $generationId = $request->query('generation_id');
        $generation = Generation::findOrFail($generationId);
        $userIds = User::where('generation_id', $generationId)->pluck('id');

        $kpiFields = [
            'kedisiplinan_nilai',
            'kesehatan_nilai',
            'safety_nilai',
            'r5_nilai',
            'vt7_nilai'
        ];

        $pieCharts = [];
        $barCharts = [];

        foreach ($kpiFields as $field) {
            // Pie Chart Data
            $currentData = Kpi::whereIn('user_id', $userIds)
                ->selectRaw("$field, COUNT(*) as count")
                ->groupBy($field)
                ->pluck('count', $field)
                ->toArray();

            $pieCharts[] = [
                'labels' => ['A', 'B', 'C', 'D'],
                'datasets' => [
                    [
                        'data' => [
                            $currentData['A'] ?? 0,
                            $currentData['B'] ?? 0,
                            $currentData['C'] ?? 0,
                            $currentData['D'] ?? 0
                        ],
                        'backgroundColor' => ['#4CAF50', '#FFEB3B', '#FF9800', '#F44336']
                    ]
                ]
            ];

            // Bar Chart Data
            $finalData = FinalKpi::whereIn('user_id', $userIds)
                ->selectRaw("$field, COUNT(*) as count")
                ->groupBy($field)
                ->pluck('count', $field)
                ->toArray();

            $barCharts[] = [
                'labels' => ['A', 'B', 'C', 'D'],
                'datasets' => [
                    [
                        'label' => 'Current',
                        'data' => [
                            $currentData['A'] ?? 0,
                            $currentData['B'] ?? 0,
                            $currentData['C'] ?? 0,
                            $currentData['D'] ?? 0
                        ],
                        'backgroundColor' => '#4CAF50'
                    ],
                    [
                        'label' => 'Final',
                        'data' => [
                            $finalData['A'] ?? 0,
                            $finalData['B'] ?? 0,
                            $finalData['C'] ?? 0,
                            $finalData['D'] ?? 0
                        ],
                        'backgroundColor' => '#2196F3'
                    ]
                ]
            ];
        }

        return response()->json([
            'pieCharts' => $pieCharts,
            'barCharts' => $barCharts
        ]);
    }
}