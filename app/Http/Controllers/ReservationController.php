<?php

namespace App\Http\Controllers;

use App\Exceptions\CanNotSendReservationRequestException;
use App\Exceptions\CantAcceptReservationException;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Enums\ReservationStatus;
use App\Models\User;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function sendReservationRequest(Request $request, User $photographer)
    {
        $user = $request->user();

        if (User::isPhotographer($user) || !User::isPhotographer($photographer))
            throw new CanNotSendReservationRequestException();

        $inputs = $request->only(['picture_count', 'reservation_time']);
        $inputs['photographer_id'] = $photographer->id;
        $inputs['status'] = ReservationStatus::PHOTOGRAPHER_WAITING;

        $reservation = $user->reservation()->create($inputs);

        return response()->json(ReservationResource::make($reservation));
    }

    public function acceptReservation(Reservation $reservation)
    {
        $user = \request()->user();

        if (!User::isPhotographer($user) || $reservation->photographer->id !== $user->id)
            throw new CantAcceptReservationException();

        $reservation->status = ReservationStatus::ACCEPTED->value;
        $reservation->save();

        return response()->json(ReservationResource::make($reservation));
    }
}
