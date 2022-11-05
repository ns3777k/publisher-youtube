<?php

namespace App\Model\Author;

use App\Model\BookCategory;
use App\Model\BookFormat;

class BookDetails
{
    private int $id;

    private string $title;

    private string $slug;

    private ?string $image;

    /**
     * @var string[]
     */
    private ?array $authors;

    private ?string $isbn;

    private ?string $description;

    private ?int $publicationDate;

    /**
     * @var BookCategory[]
     */
    private array $categories = [];

    /**
     * @var BookFormat[]
     */
    private array $formats = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string[]|null
     */
    public function getAuthors(): ?array
    {
        return $this->authors;
    }

    /**
     * @param string[]|null $authors
     */
    public function setAuthors(?array $authors): self
    {
        $this->authors = $authors;

        return $this;
    }

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

    public function getPublicationDate(): ?int
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(?int $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

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
