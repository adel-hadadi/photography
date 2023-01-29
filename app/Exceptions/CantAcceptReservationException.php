<?php

namespace App\Exceptions;

use App\Exceptions\Concerns\JsonRender;
use Exception;

class CantAcceptReservationException extends Exception
{
    use JsonRender;

    public function __construct(string $message = "you dont have access to accept reservation", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
