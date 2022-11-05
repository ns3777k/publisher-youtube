<?php

namespace App\Exception;

use RuntimeException;

class BookFormatNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('book format not found');
    }
}
