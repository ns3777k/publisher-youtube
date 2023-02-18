<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Model;

class BookCategoryListResponse
{
    /**
     * @var BookCategory[]
     */
    private array $items;

    /**
     * @param BookCategory[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return BookCategory[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
