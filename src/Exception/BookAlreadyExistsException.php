<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Exception;

class BookAlreadyExistsException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('book already exists');
    }
}
