<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Auth\Otp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\UserType;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public static function isPhotographer(User $user): bool
    {
        return self::query()->where('id', $user->id)->where('type', UserType::PHOTOGRAPHER)->exists();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'avatar',
        'latitude',
        'longitude'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type' => UserType::class
    ];

    public function reservation()
    {
        return $this->hasMany(Reservation::class);
    }

    public function otpCodes()
    {
        return $this->hasMany(Otp::class, 'user_id');
    }

}
