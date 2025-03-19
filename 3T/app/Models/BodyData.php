<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodyData extends Model
{
    use HasFactory;

    protected $table = 'body_data';

    protected $fillable = ['user_id', 'height', 'ideal_weight', 'actual_weight', 'conclusion'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
