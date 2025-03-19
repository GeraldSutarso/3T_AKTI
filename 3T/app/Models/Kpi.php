<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    use HasFactory;

    protected $table = 'kpi';

    protected $fillable = [
        'user_id', 'no_room', 'kedisiplinan_point', 'kedisiplinan_nilai',
        'kesehatan_point', 'kesehatan_nilai', 'safety_point', 'safety_nilai',
        'r5_point', 'r5_nilai', 'vt7_point', 'vt7_nilai', 'keterangan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
