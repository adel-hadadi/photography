<?php

namespace App\Exceptions;

use App\Exceptions\Concerns\JsonRender;
use Exception;
use Throwable;

class InvalidOtpCodeException extends Exception
{
    use JsonRender;

    public function __construct($message = "input code is not valid", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
