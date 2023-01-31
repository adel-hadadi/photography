<?php

namespace App\Http\Controllers;

use App\Exceptions\CanNotSendReservationRequestException;
use App\Exceptions\CantAcceptReservationException;
use App\Exceptions\CantAttachFileException;
use App\Exceptions\DontHaveAccessToAttachFileException;
use App\Http\Requests\ReservationAttachmentStoreReqeust;
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

    public function attachFilesToReservation(Reservation $reservation, ReservationAttachmentStoreReqeust $request)
    {
        $user = $request->user();

        if ($reservation->photographer_id !== $user->id)
            throw new DontHaveAccessToAttachFileException();

        if (!User::isPhotographer($user) || $reservation->status != ReservationStatus::ACCEPTED)
            throw new CantAttachFileException();

        $file = $request->file('file');

        $image['file_size'] = $file->getSize();
        $image['file_type'] = $file->getClientMimeType();
        $image['attachment'] = $file->move(public_path('images' . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . date('d')), $file->getClientOriginalName())->getRealPath();

        $attachment = $reservation->attachments()->create($image);

        $reservation->load(['attachments']);
        return response()->json($reservation);
    }
}
