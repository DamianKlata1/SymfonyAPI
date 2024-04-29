<?php

namespace App\Exception;

use RuntimeException;

class DeleteFailedException extends RuntimeException
{
    public function __construct($message = "Failed to delete the post") {
        parent::__construct($message);
    }

}