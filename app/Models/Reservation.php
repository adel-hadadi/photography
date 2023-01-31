<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;


    protected $fillable = ['picture_count', 'reservation_time', 'photographer_id', 'status'];

    protected $casts = [
        'status' => ReservationStatus::class
    ];

    public function photographer()
    {
        return $this->belongsTo(User::class, 'photographer_id');
    }

    public function attachments()
    {
        return $this->hasMany(ReservationAttachment::class);
    }
}
