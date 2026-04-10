<?php

namespace App\Exceptions;

class PromptNotFoundException extends \DomainException
{
    public function __construct($message = "PROMPT_NOT_FOUND")
    {
        parent::__construct($message);
    }
}
