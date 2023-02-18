<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Service;

class Rating
{
    public function __construct(private int $total, private float $rating)
    {
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getRating(): float
    {
        return $this->rating;
    }
}
