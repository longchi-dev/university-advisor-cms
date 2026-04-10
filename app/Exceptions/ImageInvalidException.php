<?php

namespace App\Exceptions;

class ImageInvalidException extends \DomainException
{
    public function __construct($message = "INVALID_IMAGE")
    {
        parent::__construct($message);
    }
}
