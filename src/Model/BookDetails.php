<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Model;

class BookDetails extends BaseBookDetails
{
    private float $rating;

    private int $reviews;

    private array $chapters;

    /**
     * @var BookCategory[]
     */
    private array $categories;

    /**
     * @var BookFormat[]
     */
    private array $formats;

    public function getRating(): float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReviews(): int
    {
        return $this->reviews;
    }

    public function setReviews(int $reviews): self
    {
        $this->reviews = $reviews;

        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getFormats(): array
    {
        return $this->formats;
    }

    public function setFormats(array $formats): self
    {
        $this->formats = $formats;

        return $this;
    }

    public function getChapters(): array
    {
        return $this->chapters;
    }

    /**
     * @param BookChapter[] $chapters
     *
     * @return $this
     */
    public function setChapters(array $chapters): self
    {
        $this->chapters = $chapters;

        return $this;
    }
}
