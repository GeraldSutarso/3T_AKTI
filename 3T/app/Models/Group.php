<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['id','name','prodi','gen_id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    protected $table = 'groups';
    public $timestamps = false;

    public function generations()
    {
        return $this->belongsTo(Generation::class);
    }
}
