<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'student_id',
        'group_id',
      	'two_fa_verified_at',
    ];
  
  	protected $casts = [
        'two_fa_verified_at' => 'datetime', // Ensures Laravel treats it as a DateTime object
    ];
    public $timestamps = false;
    
        public function group()
    {
        return $this->belongsTo(Group::class);
    }
    protected $table = 'users';

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
    public function generations()
    {
        return $this->hasMany(Generation::class);
    }
    public function periods()
    {
        return $this->hasMany(Period::class);
    }
    
}
