<?php

namespace J3dyy\LaravelLocalized\exceptions;

use Throwable;

class LocalizedImplementationException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
