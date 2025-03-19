<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generation extends Model
{
    use HasFactory;

    protected $fillable = ['gen'];

    public function group()
    {
        return $this->hasMany(Group::class);
    }

    public function periods()
    {
        return $this->hasMany(Period::class);
    }
}
