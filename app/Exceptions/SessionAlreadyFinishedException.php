<?php

namespace App\Exceptions;

use DomainException;

class SessionAlreadyFinishedException extends DomainException
{
    public function __construct()
    {
        parent::__construct('GAMING_SESSION_ALREADY_FINISHED');
    }
}
