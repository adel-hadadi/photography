<?php
namespace App\Enums;

enum ReservationStatus : string {
    case PHOTOGRAPHER_WAITING = "photographerWaiting";
    case ACCEPTED = "accepted";
    case CANCELED = "canceled";
    case DONE = "done";
}
