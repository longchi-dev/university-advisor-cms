<?php

namespace App\Exceptions;

class SoldOldRewardException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('SOLD_OLD_REWARD');
    }
}
