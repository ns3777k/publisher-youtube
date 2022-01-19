<?php

namespace App\Model;

class ReviewPage
{
    /**
     * @var Review[]
     */
    private array $items;

    private float $rating;

    private int $page;

    private int $pages;

    private int $perPage;

    private int $total;

    /**
     * @return Review[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param Review[] $items
     * @return ReviewPage
     */
    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function getPages(): int
    {
        return $this->pages;
    }

    public function setPages(int $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }
}
