<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Exception;

class BookChapterContentNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('book chapter content not found');
    }
}
