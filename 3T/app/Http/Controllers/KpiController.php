<?php

// namespace App\Http\Controllers;

// use App\Models\Kpi;
// use Illuminate\Http\Request;

// class KpiController extends Controller
// {
//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'user_id' => 'required|exists:users,id',
//             'period_id' => 'required|exists:periods,id',
//             'no_room' => 'nullable|string|max:255',
//             'kedisiplinan_point' => 'required|integer',
//             'kesehatan_point' => 'required|integer',
//             'safety_point' => 'required|integer',
//             'r5_point' => 'required|integer',
//             'vt7_point' => 'required|integer',
//             'keterangan' => 'nullable|string',
//         ]);

//         // Check if KPI record exists
//         $kpi = Kpi::updateOrCreate(
//             [
//                 'user_id' => $validated['user_id'],
//                 'period_id' => $validated['period_id']
//             ],
//             $validated
//         );

//         return response()->json([
//             'message' => 'KPI data saved successfully',
//             'kpi' => $kpi
//         ]);
//     }
// }


namespace App\Http\Controllers;

use App\Models\Kpi;
use App\Models\User;
use App\Models\Group;
use App\Models\Period;
use App\Models\Generation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KpiController extends Controller
{
    public function getCurrentPeriod()
    {
        $period = Period::where('is_current', true)->first();
        
        if (!$period) {
            return response()->json(['error' => 'No current period found'], 404);
        }
        
        return response()->json($period);
    }
    
    public function getKpiData(Request $request)
    {
        $periodId = $request->input('period_id');
        $excludeGroups = $request->input('exclude_groups', []);
        
        // Validate period exists
        $period = Period::findOrFail($periodId);
        
        // Get all users who don't belong to excluded groups
        $users = User::whereNotIn('group_id', $excludeGroups)
            ->with(['group.generations'])
            ->get();
            
        // Get current generation
        $firstUser = $users->first();
        $generation = $firstUser ? $firstUser->group->generations : null;
        
        // For each user, get or create KPI record for this period
        foreach ($users as $user) {
            $kpi = Kpi::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'period_id' => $periodId
                ],
                [
                    'no_room' => '',
                    'kedisiplinan_point' => 0,
                    'kedisiplinan_nilai' => 0,
                    'kesehatan_point' => 0,
                    'kesehatan_nilai' => 0,
                    'safety_point' => 0,
                    'safety_nilai' => 0,
                    'r5_point' => 0,
                    'r5_nilai' => 0,
                    'vt7_point' => 0,
                    'vt7_nilai' => 0,
                    'keterangan' => ''
                ]
            );
            
            $user->kpi = $kpi;
        }
        
        return response()->json([
            'users' => $users,
            'generation' => $generation,
            'period' => $period
        ]);
    }
    
    public function updateKpiField(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period_id' => 'required|exists:periods,id',
            'field' => 'required|string',
            'value' => 'nullable'
        ]);
        
        $userId = $request->input('user_id');
        $periodId = $request->input('period_id');
        $field = $request->input('field');
        $value = $request->input('value');
        
        // Validate field is actually a fillable field on the Kpi model
        $allowedFields = [
            'no_room', 
            'kedisiplinan_point', 'kedisiplinan_nilai',
            'kesehatan_point', 'kesehatan_nilai',
            'safety_point', 'safety_nilai',
            'r5_point', 'r5_nilai',
            'vt7_point', 'vt7_nilai',
            'keterangan'
        ];
        
        if (!in_array($field, $allowedFields)) {
            return response()->json(['error' => 'Invalid field'], 422);
        }
        
        // Get or create the KPI record
        $kpi = Kpi::firstOrCreate(
            [
                'user_id' => $userId,
                'period_id' => $periodId
            ]
        );
        
        // Update the field
        $kpi->{$field} = $value;
        $kpi->save();
        
        return response()->json(['success' => true]);
    }
    public function getPeriodThresholds(Request $request)
{
    $periodId = $request->input('period_id');
    
    // Get the thresholds for this period
    $period = Period::findOrFail($periodId);
    
    // Return all the min thresholds
    return response()->json([
        'kedisiplinan_min_a' => $period->kedisiplinan_min_a,
        'kedisiplinan_min_b' => $period->kedisiplinan_min_b,
        'kedisiplinan_min_c' => $period->kedisiplinan_min_c,
        'kedisiplinan_min_d' => $period->kedisiplinan_min_d,
        'kesehatan_min_a' => $period->kesehatan_min_a,
        'kesehatan_min_b' => $period->kesehatan_min_b,
        'kesehatan_min_c' => $period->kesehatan_min_c,
        'kesehatan_min_d' => $period->kesehatan_min_d,
        'safety_min_a' => $period->safety_min_a,
        'safety_min_b' => $period->safety_min_b,
        'safety_min_c' => $period->safety_min_c,
        'safety_min_d' => $period->safety_min_d,
        'r5_min_a' => $period->r5_min_a,
        'r5_min_b' => $period->r5_min_b,
        'r5_min_c' => $period->r5_min_c,
        'r5_min_d' => $period->r5_min_d,
        'vt7_min_a' => $period->vt7_min_a,
        'vt7_min_b' => $period->vt7_min_b,
        'vt7_min_c' => $period->vt7_min_c,
        'vt7_min_d' => $period->vt7_min_d,
    ]);
}
}