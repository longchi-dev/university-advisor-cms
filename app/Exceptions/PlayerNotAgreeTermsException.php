<?php

namespace App\Exceptions;

class PlayerNotAgreeTermsException extends \DomainException
{
    public function __construct($message = "PLAYER_NOT_AGREE_TERMS")
    {
        parent::__construct($message);
    }
}
