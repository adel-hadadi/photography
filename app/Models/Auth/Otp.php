<?php

namespace App\Models\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'user_id', 'used_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
