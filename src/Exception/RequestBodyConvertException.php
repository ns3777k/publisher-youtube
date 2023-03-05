<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Exception;

class RequestBodyConvertException extends \RuntimeException
{
    public function __construct(\Throwable $previous)
    {
        parent::__construct('error while unmarshalling request body', 0, $previous);
    }
}
