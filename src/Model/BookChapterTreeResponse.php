<?php

declare(strict_types=1);

namespace App\Model;

class BookChapterTreeResponse
{
    /**
     * @param BookChapter[] $items
     */
    public function __construct(private array $items = [])
    {
    }

    /**
     * @return BookChapter[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(BookChapter $chapter): void
    {
        $this->items[] = $chapter;
    }
}
