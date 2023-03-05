<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Exception;

class UploadFileInvalidTypeException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('uploaded file type is invalid');
    }
}
