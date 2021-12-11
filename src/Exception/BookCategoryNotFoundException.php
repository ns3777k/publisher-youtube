<?php

namespace App\Exception;

use RuntimeException;

class BookCategoryNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('book category not found');
    }
}
