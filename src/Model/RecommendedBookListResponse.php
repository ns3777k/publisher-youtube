<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Model;

class RecommendedBookListResponse
{
    /**
     * @param RecommendedBook[] $items
     */
    public function __construct(private readonly array $items)
    {
    }

    /**
     * @return RecommendedBook[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
