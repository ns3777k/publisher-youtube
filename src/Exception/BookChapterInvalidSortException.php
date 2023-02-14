<?php

namespace App\Exception;

use RuntimeException;

class BookChapterInvalidSortException extends RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
