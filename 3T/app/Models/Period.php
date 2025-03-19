<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = ['month', 'semester','gen_id', 'is_current'];

    public function generations()
    {
        return $this->belongsTo(Generation::class);
    }
    public function kpis()
    {
        return $this->hasMany(Kpi::class);
    }

    public function finalKpis()
    {
        return $this->hasMany(FinalKpi::class);
    }

    public function bodyData()
    {
        return $this->hasMany(BodyData::class);
    }

    public function finalBodyData()
    {
        return $this->hasMany(FinalBodyData::class);
    }

    public function physicals()
    {
        return $this->hasMany(Physical::class);
    }

    public function finalPhysicals()
    {
        return $this->hasMany(FinalPhysical::class);
    }
}
