<?php

namespace App\Exceptions;

class AllKeysNotAvailableException extends \Exception
{
    protected $code = 503; // HTTP Service Unavailable

    public function __construct($message = "ALL_KEYS_NOT_AVAILABLE", $code = 503) {
        parent::__construct($message, $code);
    }
}
