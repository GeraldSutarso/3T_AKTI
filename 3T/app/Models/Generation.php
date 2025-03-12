<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generation extends Model
{
    use HasFactory;

    protected $fillable = ['gen', 'group_id'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
