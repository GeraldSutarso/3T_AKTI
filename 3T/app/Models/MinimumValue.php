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
        'kedisiplinan_min',
        'kesehatan_min',
        'safety_min',
        'r5_min',
        'vt7_min',
    ];
    
    // Optional relationship if using period:
    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
