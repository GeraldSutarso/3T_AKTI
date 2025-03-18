<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BodyData;
use App\Models\Physical;
use App\Models\Kpi;
use App\Models\MinimumValue;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        // Get the latest minimum values (assuming period filtering is needed)
        $minValues = MinimumValue::latest('period_id')->first();

        // Ensure we have minValues before proceeding
        if (!$minValues) {
            return response()->json(['bodyData' => [], 'physical' => [], 'kpi' => []]);
        }

        // 🔴 Users with BodyData below bb_under or above bb_over
        $bodyDataNotifications = BodyData::where(function ($query) use ($minValues) {
                $query->where('bb', '<', $minValues->bb_under)
                      ->orWhere('bb', '>', $minValues->bb_over);
            })
            ->with('user')->get()
            ->map(fn ($data) => [
                'name' => $data->user->name,
                'category' => 'BodyData',
                'issue' => 'Out of BB-TB range',
                'value' => $data->conclusion
            ]);

        // 🟡 Users with Physical data below thresholds
        $physicalNotifications = Physical::where(function ($query) use ($minValues) {
                $query->where('avg', '<', $minValues->avg_kkm)
                      ->orWhere('bleep', '<', $minValues->bleep_kkm);
            })
            ->with('user')->get()
            ->map(fn ($data) => [
                'name' => $data->user->name,
                'category' => 'Physical',
                'issue' => 'Below minimum physical score',
                'value' => "Avg: {$data->avg}, Bleep: {$data->bleep}"
            ]);

        // 🔵 Users with KPI _point below _min_b values
        $kpiNotifications = Kpi::where(function ($query) use ($minValues) {
                $query->where('kedisiplinan_point', '<', $minValues->kedisiplinan_min_b)
                      ->orWhere('kesehatan_point', '<', $minValues->kesehatan_min_b)
                      ->orWhere('safety_point', '<', $minValues->safety_min_b)
                      ->orWhere('r5_point', '<', $minValues->r5_min_b)
                      ->orWhere('vt7_point', '<', $minValues->vt7_min_b);
            })
            ->with('user')->get()
            ->map(fn ($data) => [
                'name' => $data->user->name,
                'category' => 'KPI',
                'issue' => 'Below KPI threshold',
                'value' => "Discipline: {$data->kedisiplinan_point}, Health: {$data->kesehatan_point}, Safety: {$data->safety_point}, R5: {$data->r5_point}, VT7: {$data->vt7_point}"
            ]);

        return response()->json([
            'bodyData' => $bodyDataNotifications,
            'physical' => $physicalNotifications,
            'kpi' => $kpiNotifications
        ]);
    }
}
