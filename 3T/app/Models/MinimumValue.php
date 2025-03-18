<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinimumValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'bleep_very_good',
        'bleep_good',
        'bleep_standard',
        'bleep_bad',
        'bleep_kkm',
        'avg_kkm',
        'bb_under',
        'bb_over',
        'kedisiplinan_min_a',
        'kesehatan_min_a',
        'safety_min_a',
        'r5_min_a',
        'vt7_min_a',
        'kedisiplinan_min_b',
        'kesehatan_min_b',
        'safety_min_b',
        'r5_min_b',
        'vt7_min_b',
        'kedisiplinan_min_c',
        'kesehatan_min_c',
        'safety_min_c',
        'r5_min_c',
        'vt7_min_c',
        'kedisiplinan_min_d',
        'kesehatan_min_d',
        'safety_min_d',
        'r5_min_d',
        'vt7_min_d',
    ];
    
    // Optional relationship if using period:
    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
