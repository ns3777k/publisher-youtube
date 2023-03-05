<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Model\Author;

use App\Model\BaseBookDetails;
use App\Model\BookCategory;
use App\Model\BookFormat;

class BookDetails extends BaseBookDetails
{
    private ?string $isbn = null;

    private ?string $description = null;

    /**
     * @var BookCategory[]
     */
    private array $categories = [];

    /**
     * @var BookFormat[]
     */
    private array $formats = [];

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(?string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return BookCategory[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param BookCategory[] $categories
     */
    public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return BookFormat[]
     */
    public function getFormats(): array
    {
        return $this->formats;
    }

    /**
     * @param BookFormat[] $formats
     */
    public function setFormats(array $formats): self
    {
        $this->formats = $formats;

        return $this;
    }
}
