<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalPhysical extends Model
{
    use HasFactory;

    protected $table = 'final_physical';

    protected $fillable = ['user_id', 'period_id', 'bleep', 'pull_up', 'push_up', 'sit_up', 'shuttle', 'avg'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
