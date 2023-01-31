<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationAttachment extends Model
{
    use HasFactory;

    protected $fillable = ['attachment', 'file_type', 'file_size', 'reservation_id'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
