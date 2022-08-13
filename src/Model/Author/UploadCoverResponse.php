<?php

namespace App\Model\Author;

class UploadCoverResponse
{
    public function __construct(private string $link)
    {
    }

    public function getLink(): string
    {
        return $this->link;
    }
}
