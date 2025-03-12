<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalBodyData extends Model
{
    use HasFactory;

    protected $table = 'final_body_data';

    protected $fillable = ['user_id', 'period_id', 'height', 'ideal_weight', 'actual_weight', 'conclusion'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
