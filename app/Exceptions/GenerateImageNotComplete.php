<?php

namespace App\Exceptions;

class GenerateImageNotComplete extends \DomainException
{
    public function __construct($message = "GENERATE_IMAGE_JOB_NOT_COMPLETE")
    {
        parent::__construct($message);
    }
}
