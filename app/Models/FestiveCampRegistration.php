<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class FestiveCampRegistration extends Model
{
    protected $fillable = [
        'user_id',
        'player_name',
        'age',
        'category',
        'school',
        'guardian_name',
        'guardian_phone',
        'guardian_email',
        'payment_method',
        'payment_phone',
        'payment_reference',
        'status',
        'notes',
        'player_photo_path',
        'verification_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
