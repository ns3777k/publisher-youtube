<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Exception;

class BookChapterInvalidSortException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
