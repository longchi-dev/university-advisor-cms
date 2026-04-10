<?php

namespace App\Exceptions;

class SignatureInvalidException extends \DomainException
{
    public function __construct($message = "SIGNATURE_INVALID")
    {
        parent::__construct($message);
    }
}
