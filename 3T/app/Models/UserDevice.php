<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_identifier',
        'last_verified_at',
    ];

    // Treat last_verified_at as a Carbon date instance.
    protected $dates = [
        'last_verified_at',
    ];

    /**
     * Get the user that owns this device.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
