<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Model\Author;

class UploadCoverResponse
{
    public function __construct(private readonly string $link)
    {
    }

    public function getLink(): string
    {
        return $this->link;
    }
}
