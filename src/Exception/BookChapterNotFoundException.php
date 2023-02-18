<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;

class BookChapterNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('book chapter not found');
    }
}
