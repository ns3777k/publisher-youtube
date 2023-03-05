<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Model;

class BaseBookDetails
{
    private int $id;

    private string $title;

    private string $slug;

    private ?string $image = null;

    /**
     * @var string[]
     */
    private ?array $authors;

    private ?int $publicationDate = null;

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
     *
     * @return $this
     */
    public function setAuthors(?array $authors): self
    {
        $this->authors = $authors;

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
}
