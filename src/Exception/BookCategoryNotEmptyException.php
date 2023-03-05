<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Exception;

class BookCategoryNotEmptyException extends \RuntimeException
{
    public function __construct(int $booksCount)
    {
        parent::__construct(sprintf('book category has %d books', $booksCount));
    }
}
