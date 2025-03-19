<?php

namespace App\Http\Controllers;
use App\Models\Period;
use App\Models\User;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function getCurrentPeriodUsers()
    {
        $currentPeriod = Period::where('is_current', true)->first();

        if (!$currentPeriod) {
            return response()->json(['message' => 'No active period found'], 404);
        }

        // Fetch users with existing KPI records in the current period
        $users = User::whereHas('kpis', function ($query) use ($currentPeriod) {
            $query->where('period_id', $currentPeriod->id);
        })->with(['kpis' => function ($query) use ($currentPeriod) {
            $query->where('period_id', $currentPeriod->id);
        }])->get();

        return response()->json([
            'period' => $currentPeriod,
            'users' => $users
        ]);
    }
}
