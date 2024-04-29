<?php

namespace App\Exception;

use RuntimeException;

class ApiFetchFailedException extends RuntimeException
{
    public function __construct($message = "Failed to fetch data from API") {
        parent::__construct($message);
    }
}