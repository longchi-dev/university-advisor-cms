<?php

namespace App\Exceptions;

use Exception;

class BillInvalidException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('BILL_INVALID');
    }
}
