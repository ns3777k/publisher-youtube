<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Model;

class ErrorDebugDetails
{
    public function __construct(private readonly string $trace)
    {
    }

    public function getTrace(): string
    {
        return $this->trace;
    }
}
