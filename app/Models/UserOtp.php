<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    protected $table = 'user_otps';

    protected $fillable = [
        'phone_number',
        'otp_hash',
        'expired_at',
    ];

    protected $dates = [
        'expired_at',
    ];
}
